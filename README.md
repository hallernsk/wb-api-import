## Laravel WB API Import

Проект реализует импорт данных из внешнего API в базу данных MySQL:

- Заказы  (orders)
- Продажи (sales)  
- Доходы (incomes)  
- Склады (stocks)  

Все данные сохраняются в удалённую базу данных (aiven.io).


Для выполнения импорта используются консольные команды с необходимыми параметрами:

#### Заказы  

php artisan import:orders dateFrom dateTo  

#### Продажи

php artisan import:sales dateFrom dateTo  

#### Доходы

php artisan import:incomes dateFrom dateTo  

#### Склады  (_Выгрузка только за текущий день_)

php artisan import:stocks dateFrom


Для заполнения предоставленной БД были выполнены команды:  

import:orders 2025-06-06 2025-06-07    

import:sales 2025-06-06 2025-06-07    

import:incomes 2025-06-01 2025-06-07    

import:stocks 2025-06-20    
  
    

### Доступ к базе данных:

Хост:         mysql-wb-api-import.h.aivencloud.com  
Порт:         14136  
Имя БД:       defaultdb    
Пользователь: avnadmin    
Пароль:       AVNS_f5CS1tVQRmVXiT9KPb5    



