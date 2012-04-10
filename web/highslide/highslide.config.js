/**
*	Site-specific configuration settings for Highslide JS
*/
hs.graphicsDir = '/highslide/graphics/';
hs.creditsPosition = 'bottom left';
hs.outlineType = 'custom';
hs.fadeInOut = true;
hs.align = 'center';
hs.useBox = true;
hs.width = 600;
hs.height = 400;
hs.captionEval = 'this.none';
hs.headingEval = 'this.thumb.title';


// Add the slideshow controller
hs.addSlideshow({
	slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: false,
	overlayOptions: {
		opacity: 0.75,
		position: 'top right',
		offsetX: 190,
		offsetY: -60,
		hideOnMouseOut: false
	},
	thumbstrip: {
		mode: 'float',
		position: 'rightpanel',
		relativeTo: 'image'
	}

});

// Russian language strings
hs.lang = {
	cssDirection: 'ltr',
	loadingText: 'Загружается...',
	loadingTitle: 'Нажмите для отмены',
	focusTitle: 'Нажмите чтобы поместить на передний план',
	fullExpandTitle: 'Развернуть до оригинального размера',
	creditsText: 'Использует <i>Highslide JS</i>',
	creditsTitle: 'Перейти на домашнюю страницу Highslide JS',
	previousText: 'Предыдущее',
	nextText: 'Следующее',
	moveText: 'Переместить',
	closeText: 'Закрыть',
	closeTitle: 'Закрыть (esc)',
	resizeTitle: 'Изменить размер',
	playText: 'Слайдшоу',
	playTitle: 'Начать слайдшоу (пробел)',
	pauseText: 'Пауза',
	pauseTitle: 'Приостановить слайдшоу (пробел)',
	previousTitle: 'Предыдущее (стрелка влево)',
	nextTitle: 'Следующее (стрелка вправо)',
	moveTitle: 'Переместить',
	fullExpandText: 'Оригинальный размер',
	number: 'Изображение %1 из %2',
	restoreTitle: 'Нажмите чтобы закрыть изображение, нажмите и перетащите для изменения местоположения. Для просмотра изображений используйте стрелки.'
};

// gallery config object
var config1 = {
	slideshowGroup: 'group1',
	thumbnailId: 'thumb1',
	numberPosition: 'heading',
	transitions: ['expand', 'crossfade']
};
