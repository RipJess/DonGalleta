# PRYOECTO SITIO WEB - DON GALLETA
## Descripción
El proyecto "Don Galleta" consiste en el desarrollo de un sitio web que facilite la venta de productos de repostería. 
Este sistema está diseñado para atender las necesidades tanto de los clientes como de los administradores del negocio. 
El sitio web cuenta con un catálogo de productos con precios actualizados, un carrito de compras funcional y herramientas para 
la gestión eficiente del inventario. Además, se hizo uso de una API para implementar la opcion de pago mediante PayPal, utilizando
una cuenta demo y un entorno de sandbox para las pruebas. De igual forma, se utilzó la librería Leaflet y una API de OpenStreetMap 
para crear un mapa interactivo. Por otro lado, con el uso de fpdf es podible generar tickets de compra básicos al realizar una compra. 
El sistema está construido dentro de un contenedor de Docker con mySQL, PhpMyAdmin y Apache. Está hecho en su mayor parte con PHP, 
ademas de hacer uso de JS para acciones tipo fetch y dinamicas en el sitio web; y CSS para el diseño.

## Ejecución
Para ejecutar localmente el proyecto, seguir estos pasos:
### 1. Clonar el repositorio
```bash 
git clone https://github.com/RipJess/DonGalleta.git
```
### 2. Acceder a la base de datos
Para poder visualizar la base de datos es necesario crear un archivo ```.env```:
En el directorio raíz del proyecto, crear un archivo llamado ```.env```.
Una vez creado, dentro de el, escribir:
```env
MYSQL_ROOT_PASSWORD= {contraseña para el usuario root}
MYSQL_DATABASE= {nombre de la base de datos}
MYSQL_USER= {nombre de usuario (sin privilegios)}
MYSQL_PASSWORD= {contraseña del usuario}
```
**Sustituir las llaves y su contenido por los valores correspondientes**

### 3. Inicializar el Contenedor
Ejecutar el siguente comando y esperar a que se instalen las dependencias
```bash
docker compose --build -d
```
### 4. Comprobar estado del Contenedor
Para comprobar que el contenedor ha sido creado correctamente, ejecutar
```bash
docker ps
```
debería de aparecer algo así:
![imagen](https://github.com/user-attachments/assets/5f6a7308-6517-487a-adfe-56982f6e4102)

### 5. Visualizar el sitio web
Una vez inicializado el contenedor, ir a su navegador web preferido y en la barra de busqueda
escribir
> localhost:8085


Deberia aparecer la página inicial del sitio.

### 6. Cargar la base de datos
Una vez que es posible observar el sitio web, estará vacio, sin datos. Para agregar productos
debe ir a la base de datos y crear un usuario con permisos de administrador.
#### i. Ingresar a la base de datos
En su navegador web, escribir
> localhost:8086

Una vez ahí, colocar las credenciales correspondientes. Como recomendación,
utilizar root como usuario y colocar la contraseña para el usuario root antes
escrita en el archivo ```.env```
#### ii. Agregar las tablas
Una vez dentro, ingresar a la base de datos con el nombre antes creada en el archivo .env y
en la pestaña ```importar``` agregar el archivo ```DonGalleta.sql```.
#### iii. Usuario Admin
El archivo ```DonGalleta.sql```ya contiene unos datos y entre ellos un usuario de tipo administrador.
Sin embargo, las contraseñas estan protegidas y no es posible utilizarlas. Para crear un nuevo usuario
de tipo administrador, lo que debe hacer es ir al sitio web y registrar un nuevo usuario, por defecto, 
se creará de tipo cliente, pero desde la base de datos puede cambiar el rol para volverlo administrador.

### 7. Agregar productos
Ya teniendo un usuario de tipo administrador, puede acceder al sitio web con sus credenciales 
y obtendrá acceso a una ventana especial de administración en el que podrá agregar, eliminar y actualizar
productos, además de observar los pedidos recientemente hechos.

## Imagenes
### Pagina de inicio
![imagen](https://github.com/user-attachments/assets/345406e0-a5f8-4237-9c0b-bf1c54e653d8)
### Mapa de Ubicaciones
![imagen](https://github.com/user-attachments/assets/77414deb-a696-42ab-9602-d674232d03d9)
### Catalogo de productos
![imagen](https://github.com/user-attachments/assets/15dbce26-338d-4a77-9a23-71ff4dc29da7)
### Inicio de sesion
![imagen](https://github.com/user-attachments/assets/f0ca505d-4847-4ae1-bbd4-4f8e4e7f7bec)
### Registro de usuario
![imagen](https://github.com/user-attachments/assets/fd9049c3-eab0-47a7-9c46-79ad3344d115)
### Carrito de compras
![imagen](https://github.com/user-attachments/assets/d951cb76-d8b8-4f7c-a384-cbc7196a70f1)
![imagen](https://github.com/user-attachments/assets/b3bf3c2a-2d2a-4119-9fe3-5160466c22b7)
### API de PayPal
![imagen](https://github.com/user-attachments/assets/8740b764-92d5-4ece-9187-6ca0d41bf3bd)
![imagen](https://github.com/user-attachments/assets/32785035-d03c-46b4-8086-d0be14cc6b25)
# Tickets de compra
![imagen](https://github.com/user-attachments/assets/598cf556-fc2b-42aa-9839-03c35e215a73)
### Busqueda de productos
![imagen](https://github.com/user-attachments/assets/5e7963da-7417-499e-afb8-dee8b521213d)
### Gestor de inventario
![imagen](https://github.com/user-attachments/assets/ab7e8814-fa07-48cc-a2c7-ccf5f4765f94)
![imagen](https://github.com/user-attachments/assets/81d19871-a646-4296-86b8-1d593971142b)


