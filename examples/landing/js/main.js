$(document).ready(function()
{

	
	
	var Carousel1Opts =
	{
		delay: 2000,
		duration: 700,
		easing: 'easeInOutBack',
		mode: 'forward-circular',
		direction: '',
		pagination: true,
		pagination_img_default: 'img/page_default.png',
		pagination_img_active: 'img/page_active.png',
		start: 0,
		width: 487
	};
	$("#Carousel1").carousel(Carousel1Opts);
	
});

