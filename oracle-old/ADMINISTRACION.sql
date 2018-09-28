
--PERFIL POR DEFECTO PARA LOS USUARIOS, EN ESTE CASO PIPEMAX
DROP PROFILE USUARIO CASCADE;
CREATE PROFILE USUARIO LIMIT
    SESSIONS_PER_USER 5000
    CONNECT_TIME 5
    IDLE_TIME 5;


--******************USUARIO GENERAL DEL SISTEMA*************************
--DROP USER PIPEMAX;
CREATE USER PIPEMAX 
    IDENTIFIED BY PIPEMAX
    PROFILE USUARIO;


--********************ADMINISTRADOR DEL SISTEMA *************************
--DROP USER JEFE CASCADE;
CREATE USER JEFE 
    IDENTIFIED BY JEFE;
    
--ASIGNAR TODOS LOS PRIVILEGIOS AL USUARIO JEFE
GRANT ALL PRIVILEGES TO JEFE;   



    

    