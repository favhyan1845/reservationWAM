# reservationWAM

1. Patrones de diseño:
   a. ¿Describe y comenta 2 patrones de diseño que hayas usado en tu entorno de trabajo?

- patron de inyeccion de dependencia:

el cual se aplica dentro de ReservationController y CsvReader, donde se inyecta dependecia en los constructores de las clases.

        en ReservationController, el servicio CsvReader es inyectado.
        en CsvReader se usa el KernelInterface para asi obtener la ruta base del proyecto, para no acceder de manera directa a variables globales.

- patron de diseño repositorio:

        se aplica en el CsvReader donde hay un comportamiento igual a un repositorio, donde se va a leer los datos del archivo csv, como si se empleara una DB, aunque Symfony por lo general maneja ello con el ORM Doctrine.

b. ¿Puedes describir las ventajas e inconvenientes de los patrones sugeridos?

Inyeccion de dependencias:

Ventajas:
reutilizacion de controladores y componentes,facilidad para pruebas unitarias ya que permite usar mocks.

Inconvenientes:
sobrecarga inicial, ya que para proyectos pequeños, puede instanciar objetos de manera directa, tambien se podria presentar inconvenientes para el entendimiento de un desarrollador junior.

Patron repositorio:

Ventajas:
separar la logica del negocio del controlador, se puede modificar el repositorio por si en algun momento se requiere usar DB

Inconvenientes:
en aplicaciones pequeñas no es viable usarlo, ya que el uso de repositorio es redundante, en el caso de mi codigo el patron no contiene el aprovechamiento total del ORM.

2. SOLID:
   a. ¿Puedes describir con tus propias palabras los principios SOLID?

son los principales fundamentos de desarrollo de software que se usan con el fin de hacer codigo legible y escalable, SOLID hace referencia a cada una de sus letras como un principio.
