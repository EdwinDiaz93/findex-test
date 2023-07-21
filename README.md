<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


1. clonar el proyecto de laravel nova ejecutando el siguiente comando:

```
git clone https://github.com/EdwinDiaz93/findex-test.git
```

2. Añadir a las variables de entorno proporcionada para esta prueba:

```
NOVA_LICENSE_KEY
```

3. dentro del directorio del proyecto, reconstruir las dependencias de laravel con el comando:
```
composer install
```

4. Crer una copia del archivo env.example y renombrarlo a .env 


5. Opcional: ejecutar el comando para poblar la base de datos:
```
php artisan migrate:refresh --seed
```

6. reconstruir las dependencias de node ejecutando:

```
npm install
```

7. levantar el proyecto laravel ejecutando el comando:
```
php artisan serve
```

8. en otra terminal ejecutar el comando:
```
npm run dev
```

9. finalmente crear un usuario nova ejecutando el comando:
```
php artisan nova:user
```
esto pedira llenar unos datos para tener el usuario nova

10. ver demo en el siguiente [enlace](https://drive.google.com/file/d/19YFHyv1T7QdpsjLpQIIAJsCHxZpdzINA/view?usp=drive_link)

