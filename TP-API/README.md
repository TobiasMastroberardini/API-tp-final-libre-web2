
1. (GET) http://localhost/API/api/reviews
2. (GET) http://localhost/API/api/reviews/:ID
3. (GET) http://localhost/API/api/reviews/:ID/:subrecurso
4. (POST) http://localhost/API/api/reviews
5. (PUT) http://localhost/API/api/reviews/:ID
6. (DELETE) http://localhost/API/api/reviews/:ID
7. (GET) http://localhost/API/api/users/token

sobrecuros de endpoint 3:
1. descripcion
2. puntuacion
3. usuario
4. seguro

Para utilizar los endpoints de POST, PUT y DELETE, necesitamos un token para realizar estas operaciones. Para obtener el token utilizamos el endpoint 7 con el siguiente header:

"Authorization": "Basic d2ViYWRtaW46YWRtaW4="

("d2ViYWRtaW46YWRtaW4=" es "user:pass" codificado en base 64, donde este "user" y "pass" son un usuario y contraseña existentes en la base de datos. El usuario por defecto es "webadmin" y la contraseña "admin", y con estos datos se obtuvo el código "d2ViYWRtaW46YWRtaW4=").
Luego, cuando queramos hacer un POST/PUT/DELETE debemos utilizar el siguiente header:

"Authorization": "Bearer (token)"

El endpoint 1 también nos permite filtrar y ordenar los resultados obtenidos. Para filtrar utilizamos endpoints como los siguientes:

http://localhost/API-RESTful/api/reviews?puntuacion=4

http://localhost/API-RESTful/api/reviews?usuario=webadmin

Por último, para ordenar los elementos utilizamos:

http://localhost/API-RESTful/api/reviews?sort=puntuacion&order=desc

Los filtros y el orden se pueden combinar:

http://localhost/API-RESTful/api/reviews?sort=puntuacion&order=desc&puntuacion=4

Este es un ejemplo de un json:
{
  "reviewId": 4,
  "descripcion": "Buena atencion.",
  "puntuacion": 8,
  "usuario": "webadmin",
  "seguroId": 2
}
