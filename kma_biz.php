<?php
/**
 * KMA API PHP Library
 * @author webjey <webjeyros@gmail.com>
 * @license MIT
 * @link https://github.com/webjeyros/kma_biz
 * @link https://kma.biz/tools/api
 */

namespace cpa;

class kma
{
    public  $endpoint = "http://api.kma1.biz/";
    public  $error = false;
    private $auth_id;
    private $auth_hash;

    /**
     * kma конструктор.
     *
     * @param $auth_id
     * @param $auth_hash
     **/
    public function __construct($auth_id=null, $auth_hash=null)
    {
        if (isset($auth_id) && isset($auth_hash))
            $this->setAuthHash($auth_id, $auth_hash);

    }


    /**
     * AuthHash setter
     *
     * @param $auth_id
     * @param $auth_hash
     * @return $this
     */
    public function setAuthHash($auth_id, $auth_hash)
    {

        $this->auth_id = $auth_id;
        $this->auth_hash = $auth_hash;
        return $this;

    }

    /**
     * Запрос к API
     *
     * @param array $params
     * @return mixed|string
     */
    protected function sendRequest($params = array())
    {

        try {
            $curl = curl_init($this->endpoint);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($curl);

            if ($response===false)
                throw new \Exception(curl_errno($curl) . ': ' . curl_error($curl));

            curl_close($curl);

            $result = json_decode($response);

            if (json_last_error())
                throw new \Exception(json_last_error() . ': ' . json_last_error_msg());

            if ($result->code)
                throw new \Exception($result->code . ': ' . $result->msg);

            return $result;

        } catch (\Exception $e) {

            $this->error=true;
            return $e->getMessage();

        }

    }

    /**
     * Запрос авторизации
     *
     * @param $username
     * @param $pass
     * @return mixed|string
     */
    public  function auth($username, $pass)
    {

        return $this->sendRequest(
            array("method" => "auth",
                  "username" => $username,
                  "pass" => $pass));
    }

    /**
     * Получение списка категорий
     *
     * @return mixed|string
     */
    public function getCategories()
    {

        return $this->sendRequest(
            array("method" => "getcategories",
                  "authid" => $this->auth_id,
                  "authhash" => $this->auth_hash));
    }

    /**
     * Получение списка офферов
     *
     * @return mixed|string
     */
    public function getOffers()
    {

        return $this->sendRequest(
            array("method" => "getoffers",
                  "authid" => $this->auth_id,
                  "authhash" => $this->auth_hash));
    }


    /**
     * Получение списка лендингов для оффера
     *
     * @param int $campaignid
     * @return mixed|string
     */
    public function getLandings($campaignid = null)
    {

        return $this->sendRequest(
            array("method" => "getlandings",
                  "campaignid" => $campaignid,
                  "authid" => $this->auth_id,
                  "authhash" => $this->auth_hash));
    }

    /**
     * Получение списка прелендингов для оффера
     *
     * @param int $campaignid
     * @return mixed|string
     */
    public function getLayers($campaignid = null)
    {

        return $this->sendRequest(
            array("method" => "getlayers",
                  "campaignid" => $campaignid,
                  "authid" => $this->auth_id,
                  "authhash" => $this->auth_hash));
    }

    /**
     * Получение списка новостей для оффера или за определенную дату
     *
     * @param int $campaignid
     * @param string $date
     * @return mixed|string
     */
    public function getNews($campaignid = null, $date = null)
    {

        $param = array("method" => "getnews",
                       "authid" => $this->auth_id,
                       "authhash" => $this->auth_hash);

        if (isset($campaignid)) $param["campaignid"] = $campaignid;
        if (isset($date)) $param["date"] = $date;

        return $this->sendRequest($param);

    }

    /**
     * Получение статусов заказов.
     *
     * @param int $campaignid
     * @param string $ids
     * @return mixed|string
     */
    public function getStatuses($campaignid = null, $ids = "")
    {

        return $this->sendRequest(
            array("method" => "getstatuses",
                  "campaignid" => $campaignid,
                  "ids" => $ids,
                  "authid" => $this->auth_id,
                  "authhash" => $this->auth_hash));
    }

    /**
     * Регистрация лида
     *
     * @param string $name
     * @param string $phone
     * @param string $channel
     * @param string $price
     * @param string $ip
     * @param string $additional
     * @return mixed|string
     */
    public function addLead($name, $phone, $channel, $price, $ip, $additional=null){
        return $this->sendRequest(
            array("method" => "addlead",
                  "name" => $name,
                  "phone" => $phone,
                  "channel" => $channel,
                  "price" => $price,
                  "ip" => $ip,
                  "authid" => $this->auth_id,
                  "authhash" => $this->auth_hash,
                  "additional" => $additional));
    }

}
