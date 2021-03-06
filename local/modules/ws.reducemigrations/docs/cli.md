##### [Главная страница](../README.md)

### Интерфейс командной строки

В модуле есть возможность работать с подготовленными миграциями из командной строки. Есть несколько преимуществ 
именно этого подхода:

  1. Время выполнения миграций не ограничено таймаутом сервера
  
  2. Возможность автоматизировать обновление миграций совместно с обновлением кода. К примеру, можно интегрировать механизм обновления миграций 
  при помощи функционала перехватчиков `СУРВ Git`

##### Использование

  Файл для работы с миграцими через интерфейс командной строки расположен по пути `bitrix/tools/migrate`

  * `php migrate` - вызов помощи, отображает список доступных действий
  
  ![Помощь](cli_help.jpg)
  
  * `php migrate list` - просмотр списка подготовленных миграций
  
  ![Список миграций](cli_list.jpg)
  
  * `php migrate apply` - применение подготовленных миграций
  
  ![Применение](cli_apply.jpg)
  
  * `php migrate rollback` - откат последнего применения
  
  ![Отмена изменений](cli_rollback.jpg)

  * `php migrate history` - просмотр истории миграций
  
  ![Просмотр истории](cli_history.jpg)