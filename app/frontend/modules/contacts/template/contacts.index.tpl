        {foreach from=$StorageObj item=obj}
            <div class="contacts_stage_01">
                <span>{* Получить заголовок телефонов *}{$obj->getPhoneTitle()}</span>
                <span>{* Получить телефоны *}{$obj->getPhone()}</span>
            </div>

            <div class="contacts_stage_02">
                <span>{* Получить заголовок адреса *}{$obj->getAddressTitle()}</span>
                <span>{* Получить адрес *}{$obj->getAddress()}</span>
            </div>
        {/foreach}
