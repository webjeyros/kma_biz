<?php
/**
 * KMA API PHP Library
 * Improved refactoring with strict typing and better practices
 * @author webjey
 * @license MIT
 * @link https://github.com/webjeyros/kma_biz
 * @link https://kma.biz/tools/api
 */

// Внимание: добавлены те же комментарии, что и в оригинале

declare(strict_types=1);

namespace cpa;

class Kma
{
    private string $endpoint = "http://api.kma1.biz/";
    private ?string $authId = null;
    private ?string $authHash = null;
    private bool $error = false;

    /**
     * kma конструктор.
     *
     * @param string|null $authId
     * @param string|null $authHash
     **/
    public function __construct(?string $authId = null, ?string $authHash = null)
    {
        if ($authId !== null && $authHash !== null) {
            $this->setAuthHash($authId, $authHash);
        }
    }

    /**
     * AuthHash setter
     *
     * @param string $authId
     * @param string $authHash
     * @return self
     */
    public function setAuthHash(string $authId, string $authHash): self
    {
        $this->authId = $authId;
        $this->authHash = $authHash;
        return $this;
    }

    /**
     * Запрос к API
     *
     * @param array $params
     * @return mixed|string
     */
    protected function sendRequest(array $params = []): mixed
    {
        try {
            $curl = curl_init($this->endpoint);
            if ($curl === false) {
                throw new \RuntimeException('Failed to initialize cURL');
            }

            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $params,
            ]);

            $response = curl_exec($curl);

            if ($response === false) {
                throw new \RuntimeException(curl_error($curl), curl_errno($curl));
            }

            curl_close($curl);

            $result = json_decode($response);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException(json_last_error_msg(), json_last_error());
            }

            if (isset($result->code) && $result->code !== 0) {
                throw new \RuntimeException(sprintf('%s: %s', $result->code, $result->msg));
            }

            return $result;

        } catch (\Throwable $e) {
            $this->error = true;
            return $e->getMessage();
        }
    }

    /**
     * Запрос авторизации
     *
     * @param string $username
     * @param string $pass
     * @return mixed|string
     */
    public function auth(string $username, string $pass): mixed
    {
        return $this->sendRequest([
            "method"   => "auth",
            "username" => $username,
            "pass"     => $pass,
        ]);
    }

    /**
     * Получение списка категорий
     *
     * @return mixed|string
     */
    public function getCategories(): mixed
    {
        return $this->sendRequest([
            "method"   => "getcategories",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
        ]);
    }

    /**
     * Получение списка офферов
     *
     * @return mixed|string
     */
    public function getOffers(): mixed
    {
        return $this->sendRequest([
            "method"   => "getoffers",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
        ]);
    }

    /**
     * Получение списка лендингов для оффера
     *
     * @param int|null $campaignId
     * @return mixed|string
     */
    public function getLandings(?int $campaignId = null): mixed
    {
        $params = [
            "method"   => "getlandings",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
        ];
        if ($campaignId !== null) {
            $params["campaignid"] = $campaignId;
        }
        return $this->sendRequest($params);
    }

    /**
     * Получение списка прелендингов для оффера
     *
     * @param int|null $campaignId
     * @return mixed|string
     */
    public function getLayers(?int $campaignId = null): mixed
    {
        $params = [
            "method"   => "getlayers",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
        ];
        if ($campaignId !== null) {
            $params["campaignid"] = $campaignId;
        }
        return $this->sendRequest($params);
    }

    /**
     * Получение списка новостей для оффера или за определенную дату
     *
     * @param int|null $campaignId
     * @param string|null $date
     * @return mixed|string
     */
    public function getNews(?int $campaignId = null, ?string $date = null): mixed
    {
        $params = [
            "method"   => "getnews",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
        ];
        if ($campaignId !== null) {
            $params["campaignid"] = $campaignId;
        }
        if ($date !== null) {
            $params["date"] = $date;
        }
        return $this->sendRequest($params);
    }

    /**
     * Получение статусов заказов.
     *
     * @param int|null $campaignId
     * @param string $ids
     * @return mixed|string
     */
    public function getStatuses(?int $campaignId = null, string $ids = ""): mixed
    {
        $params = [
            "method"   => "getstatuses",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
            "ids"      => $ids,
        ];
        if ($campaignId !== null) {
            $params["campaignid"] = $campaignId;
        }
        return $this->sendRequest($params);
    }

    /**
     * Регистрация лида
     *
     * @param string $name
     * @param string $phone
     * @param string $channel
     * @param string $price
     * @param string $ip
     * @param string|null $additional
     * @return mixed|string
     */
    public function addLead(string $name, string $phone, string $channel, string $price, string $ip, ?string $additional = null): mixed
    {
        $params = [
            "method"   => "addlead",
            "authid"   => $this->authId,
            "authhash" => $this->authHash,
            "name"     => $name,
            "phone"    => $phone,
            "channel"  => $channel,
            "price"    => $price,
            "ip"       => $ip,
        ];
        if ($additional !== null) {
            $params["additional"] = $additional;
        }
        return $this->sendRequest($params);
    }

    /**
     * Проверка наличия ошибки
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error;
    }
}
