{*
    #ПАРАМЕТРЫ МЕТЕО№#
    `city_index` INT(11) NOT NULL COMMENT '      уникальный пятизначный код города',
     `date`  'дата, на которую составлен прогноз в данном блоке',
     `hour`  'местное время, на которое составлен прогноз',
     `tod`  'время суток, для которого составлен прогноз: 0 - ночь 1 - утро, 2 - день, 3 - вечер',
     `phenomena_cloudiness`       облачность по градациям:  0 - ясно, 1- малооблачно, 2 - облачно, 3 - пасмурно',
     `phenomena_precipitation`       тип осадков: 4 - дождь, 5 - ливень, 6,7 – снег, 8 - гроза, 9 - нет данных, 10 - без осадков',
     `phenomena_rpower`       интенсивность осадков, если они есть. 0 - возможен дождь/снег, 1 - дождь/снег',
     `phenomena_spower`       вероятность грозы, если прогнозируется: 0 - возможна гроза, 1 - гроза',
     `pressure_max` 'атмосферное давление, в мм.рт.ст.',
     `pressure_min`  'атмосферное давление, в мм.рт.ст.',
     `temperature_max`      температура воздуха, в градусах Цельсия',
     `temperature_min`       температура воздуха, в градусах Цельсия',
     `wind_max`       приземный ветер',
     `wind_min`      приземный ветер',
     `wind_direction`       направление ветра в румбах, 0 - северный, 1 - северо-восточный,  и т.д.',
     `relwet_max`       относительная влажность воздуха, в %',
     `relwet_min`       относительная влажность воздуха, в %',
     `heat_max`       комфорт - температура воздуха по ощущению одетого по сезону человека, выходящего на улицу',
     `heat_min`       комфорт - температура воздуха по ощущению одетого по сезону человека, выходящего на улицу',

*}

{if $meteo}
         {*
         выводим объект $meteo с нужными параметрами указзаные выше
         *}
{foreach from=$meteo item=inst}
<ul class="list_c">
<li>
    <div class="ww_left">
        <img src="{$inst->img}" alt="Погода"/>
    </div>
    <div class="ww_right">
    <h5>{$inst->attributes()->date|date_format:"%e %B %Y"}</h5>
    <ul>
    <li>
        Температура, <span class="celsium">°C</span><span class="ww_item">{$inst->temperature_min}-{$inst->temperature_max}</span>
    </li>
    <li>
        Влажность, % <span class="ww_item">{$inst->relwet_min}-{$inst->relwet_max}</span>
    </li>
    <li>
        Ветер, м/сек <span class="ww_item">{$inst->wind_min}-{$inst->wind_max}</span>
    </li>
    <li>
        Давление, мм <span class="ww_item">{$inst->pressure_min}-{$inst->pressure_max}</span>
    </li>
    </ul>
    </div>
</li>
</ul>
{/foreach}
{else}
Нет метеоданных
{/if}