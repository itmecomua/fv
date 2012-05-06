                    <div class="but1 active">
                        <a onclick="javascript: window.location.href = '/news/'">Все новости</a>
                    </div>
            <div class="static_page">
                <h1>{$iNews->getName()}</h1>
                <h2>{$iNews->getHeading()}</h2>
                <p>{$iNews->getText()}</p>
                <div class="view">Просмотров: {$iNews->shows}  Дата: {$iNews->create_time|date_format:'%d.%m.%Y %H:%M'}</div>
            </div>
