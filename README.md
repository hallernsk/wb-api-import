## Laravel WB API Import

Проект реализует импорт данных из внешнего API в базу данных MySQL:

- Заказы  (orders)
- Продажи (sales)  
- Доходы (incomes)  
- Склады (stocks)  

Все данные сохраняются в удалённую базу данных (freesqldatabase.com).


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

import:stocks 2025-06-08    
  
    

### Доступ к базе данных:

Хост:         sql7.freesqldatabase.com  
Порт:         3306  
Имя БД:       sql7783554    
Пользователь: sql7783554    
Пароль:       q1aKbE9CGq    

Для phpmyadmin:  
http://www.phpmyadmin.co  







