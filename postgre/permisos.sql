﻿-- PRIMERO ES NECESARIO REVOCAR TODOS LOS PRIVILEGIOS DE LOS USUARIOS AL CREAR UNA BASE DE DATOS
-- REVOKE ALL PRIVILEGES ON DATABASE "database_name" from "username"; 

-- NOS CONECTAMOS CON EL USUARIO SUPERUSER DE POSTGRES PARA CREAR LOS SIGUIENTES 2 USUARIOS

create role JEFE with nocreatedb nocreaterole nologin noinherit password 'JEFE';	
create role PIPEMAX with nocreatedb nocreaterole nologin noinherit password 'PIPEMAX';

-- SE OTORGAN TODOS LOS PRIVILEGIOS AL JEFE
GRANT ALL PRIVILEGES ON SCHEMA PUBLIC TO JEFE;

-- SE OTORGA EL PRIVILEGIO DE CONEXIÓN Y USO DE LA BASE DE DATOS "arriendo" A PIPEMAX;
GRANT CONNECT ON DATABASE arriendo TO pipemax;
GRANT USAGE ON SCHEMA public to pipemax;
GRANT SELECT ON ALL TABLES IN SCHEMA public TO pipemax;

/*SE REVOCAN TODOS LOS PERMISOS DE CRUD PARA PIPEMAX 
  POSTERIORMENTE SE ASIGNARÁN DE ACUERDO A LO QUE EL USUARIO "EXTERIOR" PUEDE HACER
*/
REVOKE INSERT ON ALL TABLES IN SCHEMA PUBLIC FROM PIPEMAX;
REVOKE DELETE ON ALL TABLES IN SCHEMA PUBLIC FROM PIPEMAX;
REVOKE UPDATE ON ALL TABLES IN SCHEMA PUBLIC FROM PIPEMAX;


-- SE OTORGAN LOS PERMISOS DE SELECT A PIPEMAX SOBRE LAS TABLAS
GRANT SELECT ON public.usuario TO pipemax;
GRANT SELECT ON public.arriendo TO pipemax;
GRANT SELECT ON public.detalle TO pipemax;
GRANT SELECT ON public.herramienta TO pipemax;
GRANT SELECT ON public.categoria TO pipemax;
GRANT SELECT ON public.sucursal TO pipemax;
GRANT SELECT ON public.carrito TO pipemax;
GRANT SELECT ON public.sucursal_herramienta TO pipemax;
GRANT INSERT ON public.usuario TO pipemax;
GRANT INSERT ON public.carrito TO pipemax;
GRANT UPDATE ON public.carrito TO pipemax;
GRANT DELETE ON public.carrito TO pipemax;
GRANT USAGE ON SEQUENCE ARRIENDO_AI TO pipemax;
GRANT INSERT ON public.arriendo TO pipemax;
GRANT UPDATE ON public.arriendo TO pipemax;
GRANT INSERT ON public.detalle TO pipemax;




    

    