<?php
class NewsAction extends fvAction
{

        function __construct ()
        {
            parent::__construct(fvSite::$Layoult);
        }

        function executeIndex()
        {
            if (!fvRequest::getInstance()->isXmlHttpRequest())
            {
                return self::$FV_OK;
            }
            else
            {
                return self::$FV_AJAX_CALL;
            }
        }

        function executeEdit()
        {
            if (fvRequest::getInstance()->isXmlHttpRequest())
                return self::$FV_AJAX_CALL;
            else
                return self::$FV_OK;
        }

        function executeSave()
        {         
            $request = fvRequest::getInstance();
            $data = $request->getRequestParameter('m','array');
            $meta = $request->getRequestParameter('meta','array');

            $ex = ( $pk = $request->getRequestParameter( 'id', 'int' ) ) 
            ? NewsManager::getInstance()->getByPk( $pk ) 
            : new News();    //создаем новый экземпляр класса или получаем текущий

            $isNew = $ex->isNew();
            
            if( $ex )
            {
                $ex->addField('oldImage', 'string', $ex->image);
                $ex->UpdateFromRequest( $data ); // инициализируем сущность
                $ex->getMeta()->updateFromRequest($meta);
                if ($ex->setMeta($ex->getMeta()) && $ex->save() ) //пробуем сохранить
                {     
                    if ( $request->getRequestParameter( 'redirect' ) )     //если нажали Сохранить и выйти, то делаем redirect
                        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
                    else
                        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/edit/?id=". $ex->getPk());
                   
                    $this->setFlash("Данные сохранены", self::$FLASH_SUCCESS);
                }
                else        //ошибка при сохранении
                {
                    $this->setFlash("Ошибка при сохранении данных", self::$FLASH_ERROR);
                    fvResponce::getInstance()->setHeader('X-JSON', json_encode($ex->getValidationResult()));
                }
            }
            else
            {
                $this->setFlash("Ошибка при сохранении данных. Такой записи не существует",self::$FLASH_ERROR); //выводим список ошибок
            }

            if (fvRequest::getInstance()->isXmlHttpRequest())
                return self::$FV_AJAX_CALL;
            else
                return self::$FV_OK;
        }

        function executeDelete()
        {
            $request = fvRequest::getInstance();
            $id = intval($request->getRequestParameter('id'));
            if ( !$ex = NewsManager::getInstance()->getByPk( $id ) )
            {
                $this->setFlash("Ошибка при удаленни данных. Такой записи не существует", self::$FLASH_ERROR);
            }
            else
            {
                $ex->getMeta()->delete();  
                $ex->delete();
                $this->setFlash("Данные удалены", self::$FLASH_SUCCESS);
            }

            fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
            if (fvRequest::getInstance()->isXmlHttpRequest())return self::$FV_AJAX_CALL;
            else return self::$FV_OK;
        }  
    
}