#REGLAS DE INSTALACIÓN DE ESTE PROYECTO

1. Descargar el proyecto de GitHub

Descargar el proyecto de GitHub. Clonamos el proyecto en nuestro VSC.

2. Borrar la carpeta .git de existir. Recuerda configurar tu explorador de archivos para que permita ver elementos ocultos

![Ver elementos ocultos](/readme/image1.png)

![Eliminar .git](/readme/image2.png)

3. Instalar las dependencias de composer y nom.

- Instalación dependencias de PHP

```bash
composer update
```

- Instalación de dependencias de javascript (node modules)

```bash
npm instal
```

4. Creación del archivo enviroment (.env)

Creamos el archivo .env y usamos estas claves de variables

```bash
# CREDENCIALES DE PHP MAILER (envioPhpMailer.php)
USERNAME={usuario del correo, que suele ser el email}
PASS={contraseña del correo}
HOST={servidor del correo saliente}
 
# CONFIGURACIÓN DE CORREOS DE ESTE PROYECTO (gestionForm.php)
EMAIL_WEB={email de que se envía}
EMAIL_ADMIN={correo destinatario del admin de la web}
```

5. Arrancar servidor

    - Para poder usar este proyecto en dev mode, debes levantar un server local en el puerto 8000 (tiene proxy al 3000)

    ```bash
    php -S localhost:8000
    ```

    - En otra terminal ejecutas el npm run dev

    ```bash
    npm run dev
    ```

De esta manera ya tendriamos en el navegador en el localhost:3000 la página en modo dev.

6. Subir proyecto