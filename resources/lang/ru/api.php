<?php
return [
    'refresh_token_invalid' => 'Your session has expired. Please log in again.',
    'Otp code successfully send'=>'OTP код успешно отправлен',
    'OTP code is wrong' => 'Неверный OTP код',
    'OTP code is expired' => 'Срок действия OTP кода истёк',
    'Email successfully verified' => 'Электронная почта успешно подтверждена',
    'Password incorrect' => 'Неверный пароль',
    'Login is successfully' => 'Вход выполнен успешно',
    'Token was successfully changed'=>'Токен успешно изменён',
    'Password reset link has been sent to your email address'=>'Ссылка для сброса пароля отправлена на вашу электронную почту',
    'Please click the link below and set a new password'=>'Пожалуйста, перейдите по ссылке ниже и установите новый пароль',
    'Log in'=>'Войти',
    'Password has been successfully updated'=>'Пароль успешно обновлён',
    'Token is incorrect or expired'=>'Токен неверен или истёк',
    'Your order has been successfully canceled'=>'Ваш заказ был успешно отменён',
    'Value not found'=>'Значение не найдено',
    'Review has been successfully added'=>'Отзыв успешно добавлен',
    'Successfully subscribed'=>'Успешная подписка',
    'OTP sent'=>'OTP отправлен',
    'Email address has been successfully changed'=>'Адрес электронной почты успешно изменён',
    'Products have been successfully added to the cart' => 'Товары успешно добавлены в корзину',
    'Product has been removed from the cart'=>'Товар удалён из корзины',
    'Product quantity has been successfully updated'=>'Количество товара успешно обновлено',
    'Profile has been successfully updated'=>'Профиль успешно обновлён',
    'Products have been successfully added to your favorites'=>'Товары успешно добавлены в избранное',
    
    'register.name.required' => 'Поле "Имя" обязательно.',
    'register.email.required' => 'Поле "Электронная почта" обязательно.',
    'register.email.email' => 'Пожалуйста, введите правильный адрес электронной почты.',
    'register.email.unique' => 'Этот адрес электронной почты уже занят.',
    'register.phone.required' => 'Поле "Телефон" обязательно.',
    'register.password.required' => 'Поле "Пароль" обязательно.',
    'register.password.regex' => 'Пароль должен содержать не менее 8 символов и включать как минимум 1 заглавную букву, 1 строчную букву, 1 цифру и 1 специальный символ.',
    'register.password.min' => 'Пароль должен содержать не менее 8 символов.',
    'email_verified' => 'Электронная почта уже подтверждена.',
    'token.required' =>  'Поле токена обязательно для заполнения',

    'first_name.required' => 'Поле имени обязательно.',
    'first_name.string' => 'Имя должно быть строкой.',
    'first_name.max' => 'Имя не должно превышать 255 символов.',

    'last_name.required' => 'Поле фамилии обязательно.',
    'last_name.string' => 'Фамилия должна быть строкой.',
    'last_name.max' => 'Фамилия не должна превышать 255 символов.',

    'email.required' => 'Поле электронной почты обязательно.',
    'email.email' => 'Пожалуйста, введите корректный адрес электронной почты.',
    'email.max' => 'Электронная почта не должна превышать 255 символов.',

    'phone.required' => 'Поле телефона обязательно.',
    'phone.string' => 'Телефон должен быть строкой.',
    'phone.max' => 'Телефон не должен превышать 20 символов.',

    'delivery_type.required' => 'Поле типа доставки обязательно.',
    'delivery_type.string' => 'Тип доставки должен быть строкой.',
    'delivery_type.in' => 'Тип доставки должен быть home_delivery или store_pickup.',

    'cart_items.required' => 'Корзина не может быть пустой.',
    'cart_items.array' => 'Элементы корзины должны быть массивом.',

    'cart_items.*.product_id.required' => 'Продукт обязателен.',
    'cart_items.*.product_id.exists' => 'Выбранный продукт не существует.',

    'cart_items.*.quantity.required' => 'Количество обязательно.',
    'cart_items.*.quantity.integer' => 'Количество должно быть целым числом.',
    'cart_items.*.quantity.min' => 'Количество должно быть не менее 1.',

    'cart_items.*.price.required' => 'Цена обязательна.',
    'cart_items.*.price.numeric' => 'Цена должна быть числом.',
    'cart_items.*.price.min' => 'Цена не может быть отрицательной.',

    'products_not_in_cart' => 'Один или несколько продуктов отсутствуют в вашей корзине',
    'not_found' => 'Такого продукта не существует',
    'Order placed successfully' => 'Заказ успешно оформлен',


    'order_id.required' => 'ID заказа обязателен.',
    'order_id.exists' => 'Такой заказ не существует.',
    'reason.max' => 'Причина отмены не может превышать 5000 символов.',
    'Profile has been successfully updated' => 'Профиль успешно обновлён',
    'products' => [
        'required' => 'Пожалуйста, укажите продукты.',
        'array' => 'Продукты должны быть в формате массива.',
        'min' => 'Необходимо выбрать как минимум один продукт.',

        'id_required' => 'ID продукта обязателен.',
        'id_exists' => 'Выбранный продукт не существует.',
        'id_integer' => 'ID продукта должен быть числом.',

        'quantity_required' => 'Для каждого продукта требуется количество.',
        'quantity_integer' => 'Количество должно быть числом.',
        'quantity_min' => 'Количество должно быть не менее 1.',
    ],

    'One or more products are not in your cart'=>'Один или несколько товаров отсутствуют в вашей корзине.',
    'order_cannot_be_cancelled_preparing'=>'Продукт уже готовится. Поэтому заказ не может быть отменён.',
    'product_not_found'=>'Такого продукта не существует.',
    'product_out_of_stock' => ' Такого продукта нет в наличии.',
    'order_already_cancelled'=>'Заказ уже был отменён.',
    'Email Verification' => 'Подтверждение электронной почты',
    'Your OTP code is:' => 'Ваш код OTP:',
    'This code is valid for :minutes minutes.' => 'Этот код действителен в течение :minutes минут.',
    'Best regards,' => 'С уважением,',
    'All rights reserved' => 'Все права защищены',


    'Reset Password' => 'Сбросить пароль',
    'Please click the link below and set a new password' => 'Пожалуйста, нажмите на ссылку ниже и установите новый пароль',
    'If you did not request a password reset, no further action is required.' => 'Если вы не запрашивали сброс пароля, никаких действий не требуется.',
    'Regards' => 'С уважением',

    'Yeni sifariş yaradıldı' => 'Создан новый заказ',
    'Sizin sifarişinizin statusu yeniləndi'=>' Статус вашего заказа был обновлён',
    'Sifarişiniz ləğv olundu' => 'Ваш заказ был отменён',



];
