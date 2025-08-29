<?php
return [
    'refresh_token_invalid' => 'Sessiyanızın vaxtı bitdi. Zəhmət olmasa yenidən daxil olun',
    'Otp code successfully send'=>'OTP kodu uğurla göndərildi',
    'OTP code is wrong' => 'OTP kodu yanlışdır',
    'OTP code is expired' => 'OTP kodunun müddəti bitib',
    'Email successfully verified' => 'E-poçt uğurla təsdiqləndi',
    'Password incorrect' => 'Şifrə yanlışdır',
    'Login is successfully' => 'Giriş uğurla başa çatdı',
    'Token was successfully changed'=>'Uğurla token dəyişdirildi',
    'Password reset link has been sent to your email address'=>'Şifrə sıfırlama linki e-poçt ünvanınıza göndərildi',
    'Please click the link below and set a new password'=>'Zəhmət olmasa, aşağıdakı linkə daxil olub yeni şifrə təyin edin',
    'Log in'=>'Daxil ol',
    'Password has been successfully updated'=>'Şifrə uğurla yeniləndi',
    'Token is incorrect or expired'=>'Token yanlışdır və ya müddəti bitmişdir',
    'Your order has been successfully canceled'=>'Sifarişiniz uğurla ləğv edildi',
    'Value not found'=>'Dəyər tapılmadı',
    'Review has been successfully added'=>'Rəy uğurla əlavə olundu',
    'Successfully subscribed'=>'Uğurla abunə olundu',
    'OTP sent'=>'OTP göndərildi',
    'Email address has been successfully changed'=>'E-poçt ünvanı uğurla dəyişdirildi',
    'Products have been successfully added to the cart' => 'Məhsullar uğurla səbətə əlavə olundu',
    'Product has been removed from the cart'=>'Məhsul səbətdən silindi',
    'Product quantity has been successfully updated'=>'Məhsul sayı uğurla yeniləndi',
    'Profile has been successfully updated'=>'Profil uğurla yeniləndi',
    'Products have been successfully added to your favorites'=>'Məhsullar uğurla bəyəndiklərimə əlavə olundu',
    

    'register.name.required' => 'Ad sahəsi mütləqdir.',
    'register.email.required' => 'E-poçt sahəsi mütləqdir.',
    'register.email.email' => 'Düzgün e-poçt ünvanı daxil edin.',
    'register.email.unique' => 'Bu e-poçt ünvanı artıq istifadə olunur.',
    'register.phone.required' => 'Telefon nömrəsi mütləqdir.',
    'register.password.required' => 'Şifrə sahəsi mütləqdir.',
    'register.password.regex' => 'Şifrə ən azı 8 simvoldan ibarət olmalı və ən azı 1 böyük hərf, 1 kiçik hərf, 1 rəqəm və 1 xüsusi simvol daxil olmalıdır.',
    'register.password.min' => 'Şifrə ən azı 8 simvoldan ibarət olmalıdır.',
    'email_verified' => 'E-poçt artıq təsdiqlənib.',

    'name.required' => 'Ad sahəsi mütləqdir.',
    'email.required' => 'E-poçt sahəsi mütləqdir.',
    'email.email' => 'Düzgün e-poçt ünvanı daxil edin.',
    'email.unique' => 'Bu e-poçt ünvanı artıq istifadə olunur.',
    'phone.required' => 'Telefon nömrəsi mütləqdir.',
    'password.required' => 'Şifrə sahəsi mütləqdir.',
    'password.regex' => 'Şifrə ən azı 8 simvoldan ibarət olmalı və ən azı 1 böyük hərf, 1 kiçik hərf, 1 rəqəm və 1 xüsusi simvol daxil olmalıdır.',
    'password.min' => 'Şifrə ən azı 8 simvoldan ibarət olmalıdır.',
    'email.exists' => 'Bu e-poçt ünvanı tapılmadı.',
    'otp_code.required' => 'OTP kodu mütləqdir.',
    'otp_code.digits' => 'OTP kodu 6 rəqəmdən ibarət olmalıdır.',
    'token.required' =>  'Token sahəsi mütləqdir.',
    'password.confirmed' => 'Şifrə təsdiqi uyğun deyil.',



    'first_name.required' => 'Ad sahəsi mütləqdir.',
    'first_name.string' => 'Ad yalnız mətn formatında olmalıdır.',
    'first_name.max' => 'Ad maksimum 255 simvol ola bilər.',

    'last_name.required' => 'Soyad sahəsi mütləqdir.',
    'last_name.string' => 'Soyad yalnız mətn formatında olmalıdır.',
    'last_name.max' => 'Soyad maksimum 255 simvol ola bilər.',

    'email.max' => 'E-poçt maksimum 255 simvol ola bilər.',

    'phone.string' => 'Telefon nömrəsi yalnız mətn formatında olmalıdır.',
    'phone.max' => 'Telefon nömrəsi maksimum 20 simvol ola bilər.',

    'delivery_type.required' => 'Çatdırılma növü mütləqdir.',
    'delivery_type.string' => 'Çatdırılma növü mətn olmalıdır.',
    'delivery_type.in' => 'Çatdırılma növü yalnız home_delivery və ya store_pickup ola bilər.',

    'cart_items.required' => 'Səbət boş ola bilməz.',
    'cart_items.array' => 'Səbət elementləri massiv olmalıdır.',

    'cart_items.*.product_id.required' => 'Məhsul seçilməlidir.',
    'cart_items.*.product_id.exists' => 'Seçilmiş məhsul mövcud deyil.',

    'cart_items.*.quantity.required' => 'Məhsul miqdarı mütləqdir.',
    'cart_items.*.quantity.integer' => 'Məhsul miqdarı tam ədəd olmalıdır.',
    'cart_items.*.quantity.min' => 'Məhsul miqdarı ən azı 1 olmalıdır.',

    'cart_items.*.price.required' => 'Məhsul qiyməti mütləqdir.',
    'cart_items.*.price.numeric' => 'Məhsul qiyməti rəqəm olmalıdır.',
    'cart_items.*.price.min' => 'Məhsul qiyməti mənfi ola bilməz.',
    'products_not_in_cart' => 'Bir və ya bir neçə məhsul səbətinizdə yoxdur',
    'not_found' => 'Belə bir məhsul mövcud deyil',
    'Order placed successfully' => 'Sifariş uğurla yerinə yetirildi',


    'order_id.required' => 'Sifariş ID-si tələb olunur.',
    'order_id.exists' => 'Belə bir sifariş mövcud deyil.',
    'reason.max' => 'Ləğv səbəbi maksimum 5000 simvol ola bilər.',
    'Profile has been successfully updated' => 'Profil uğurla yeniləndi',
    'products' => [
        'required' => 'Zəhmət olmasa, məhsulları daxil edin.',
        'array' => 'Məhsullar siyahı formatında olmalıdır.',
        'min' => 'Ən az bir məhsul seçilməlidir.',
        'id_required' => 'Hər bir məhsul üçün ID vacibdir.',
        'id_exists' => 'Seçilmiş məhsul mövcud deyil.',
        'id_integer' => 'Məhsul ID-si rəqəm olmalıdır.',

        'quantity_required' => 'Hər bir məhsul üçün say vacibdir.',
        'quantity_integer' => 'Məhsul sayı rəqəm olmalıdır.',
        'quantity_min' => 'Məhsul sayı ən az 1 olmalıdır.',
    ],


    'Email Verification' => 'Email Təsdiqi',
    'Your OTP code is:' => 'Sizin OTP kodunuz:',
    'This code is valid for :minutes minutes.' => 'Bu kod :minutes dəqiqə ərzində etibarlıdır.',
    'Best regards,' => 'Hörmətlə,',
    'All rights reserved' => 'Bütün hüquqlar qorunur',

    'Reset Password' => 'Şifrəni Sıfırla',
    'Please click the link below and set a new password' => 'Yeni şifrə təyin etmək üçün aşağıdakı linkə klikləyin',
    'If you did not request a password reset, no further action is required.' => 'Əgər şifrə sıfırlama tələb etməmisinizsə, heç bir əməliyyat icra etmək lazım deyil.',
    'Regards' => 'Hörmətlə',
    'Yeni sifariş yaradıldı'=>'Yeni sifariş yaradıldı',
    
    'One or more products are not in your cart'=>'Səbətinizdə məhsul yoxdur.',
    'order_cannot_be_cancelled_preparing'=>'Məhsul artıq hazırlanır. Bu səbəbdən sifariş ləğv edilə bilməz.',
    'product_not_found'=>'Belə bir məhsul mövcud deyil.',
    'product_out_of_stock'=>' adlı məhsul stokda yoxdur.',
    'order_already_cancelled'=>'Sifariş onsuzda ləğv olunub.',
    'Sizin sifarişinizin statusu yeniləndi'=>'Sizin sifarişinizin statusu yeniləndi',
    'Sifarişiniz ləğv olundu' => 'Sifarişiniz ləğv olundu',
];
