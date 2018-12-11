--
-- PostgreSQL database dump
--

-- Dumped from database version 10.5
-- Dumped by pg_dump version 10.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


--
-- Name: actualiza_total(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualiza_total() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    CANTIDAD_DIAS INT;
BEGIN    
    SELECT DATE_PART('day',AGE(OLD.FECHA_FINAL,OLD.FECHA_INICIO)) INTO CANTIDAD_DIAS;
    UPDATE ARRIENDO
    SET TOTAL = (OLD.TOTAL * CAST(CANTIDAD_DIAS AS INT))
    WHERE COD_ARRIENDO = OLD.COD_ARRIENDO;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualiza_total() OWNER TO jefe;

--
-- Name: actualiza_total_arriendo(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualiza_total_arriendo() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    SUCURSAL INT;
    CANTIDAD_DIAS INT;
BEGIN    
    UPDATE ARRIENDO
    SET TOTAL = TOTAL + NEW.TOTAL_DETALLE
    WHERE COD_ARRIENDO = NEW.ID_A;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualiza_total_arriendo() OWNER TO jefe;

--
-- Name: actualizar_administrador(integer, character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_administrador(rut_ad integer, nombres_a character varying, apellidos_a character varying, comuna_a integer, correo_a character varying, celular_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    NOMBRE_USER VARCHAR(100);
BEGIN
    IF(CHECKADMIN(RUT_AD)=TRUE) THEN
        SELECT CONCAT(CONCAT(NOMBRES,' '),APELLIDOS) INTO NOMBRE_USER FROM ADMINISTRADOR WHERE RUT = RUT_AD;
        LOCK TABLE ADMINISTRADOR IN ROW EXCLUSIVE MODE;
        UPDATE ADMINISTRADOR
        SET NOMBRES=NOMBRES_A,APELLIDOS=APELLIDOS_A,CORREO=CORREO_A,CELULAR=CELULAR_U,COMUNA=COMUNA_A
        WHERE RUT=RUT_AD;
        BOOL := 'TRUE';
        MESSAGE := 'EL USUARIO: '||NOMBRE_USER||' HA SIDO MODIFICADO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL USUARIO QUE INTENTA MODIFICAR NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA MODIFICAR USUARIOS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_administrador(rut_ad integer, nombres_a character varying, apellidos_a character varying, comuna_a integer, correo_a character varying, celular_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_categoria(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_categoria(codigo integer, nombre_c character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ DECLARE
BEGIN
    IF(CHECKCATEGORIA(CODIGO)=TRUE) THEN
        LOCK TABLE CATEGORIA IN ROW EXCLUSIVE MODE;
        UPDATE CATEGORIA
        SET NOMBRE=NOMBRE_C
        WHERE COD_CATEGORIA=CODIGO;        
        BOOL := 'TRUE';
        MESSAGE := 'LA CATEGORIA CON CÓDIGO: '||CODIGO||' SE HA ACTUALIZADO CORRECTAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA CATEGORIA QUE INTENTA ACTUALIZAR NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
        WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR LA CATEGORIA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_categoria(codigo integer, nombre_c character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_empresa(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_empresa(cod_emp integer, nombre_s character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_EMPRESA VARCHAR(100);
BEGIN
    IF(CHECKEMPRESA(CODIGO_EMP)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_EMPRESA FROM EMPRESA WHERE COD_EMPRESA = COD_EMP;
        LOCK TABLE EMPRESA IN ROW EXCLUSIVE MODE;
        UPDATE EMPRESA
        SET NOMBRE=NOMBRE_S
        WHERE COD_EMPRESA=COD_EMP;        
        BOOL := 'TRUE';
        MESSAGE := 'LA ACTUALIZACIÓN DE LA EMPRESA: '||NOMBRE_EMPRESA||' SE HA REALIZADO CON ÉXITO';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'NO SE PUDO ACTUALIZAR LA EMPRESA YA QUE NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR LA EMPRESA';
            RETURN;
        WHEN OTHERS THEN    
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_empresa(cod_emp integer, nombre_s character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_empresa(integer, character varying, integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_empresa(cod_emp integer, nombre_s character varying, region_s integer, ciudad_s character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_EMPRESA VARCHAR(100);
BEGIN
    IF(CHECKEMPRESA(CODIGO_EMP)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_EMPRESA FROM EMPRESA WHERE COD_EMPRESA = COD_EMP;
        LOCK TABLE EMPRESA IN ROW EXCLUSIVE MODE;
        UPDATE EMPRESA
        SET NOMBRE=NOMBRE_S,REGION=REGION_S,CIUDAD=CIUDAD_S
        WHERE COD_EMPRESA=COD_EMP;        
        BOOL := 'TRUE';
        MESSAGE := 'LA ACTUALIZACIÓN DE LA EMPRESA: '||NOMBRE_EMPRESA||' SE HA REALIZADO CON ÉXITO';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'NO SE PUDO ACTUALIZAR LA EMPRESA YA QUE NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR LA EMPRESA';
            RETURN;
        WHEN OTHERS THEN    
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_empresa(cod_emp integer, nombre_s character varying, region_s integer, ciudad_s character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_h_sucursal(integer, integer, integer, integer, integer, integer, date, date); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_h_sucursal(codigo integer, codigo_s integer, stock_h integer, precio_h integer, empresa_h integer, descuento_h integer, inicio_h date, fin_h date, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_H VARCHAR(100);
    NOMBRE_S VARCHAR(100);
BEGIN
    IF(STOCK_H>0) THEN
        IF(PRECIO_H)>0 THEN
            IF(CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE AND CHECKSUCURSAL(CODIGO_S,EMPRESA_H)=TRUE AND CHECKEMPRESA(EMPRESA_H)=TRUE) THEN
                SELECT NOMBRE INTO NOMBRE_H FROM HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND EMPRESA = EMPRESA_H;
                SELECT NOMBRE INTO NOMBRE_S FROM SUCURSAL WHERE COD_SUCURSAL = CODIGO_S AND COD_EMPRESA = EMPRESA_H;
                LOCK TABLE SUCURSAL_HERRAMIENTA IN ROW EXCLUSIVE MODE;
                UPDATE SUCURSAL_HERRAMIENTA 
                SET STOCK = STOCK_H, PRECIO = PRECIO_H, DESCUENTO = DESCUENTO_H, F_INICIO_D = INICIO_H, F_FINAL_D = FIN_H
                WHERE COD_HERRAMIENTA = CODIGO AND COD_SUCURSAL = CODIGO_S;        
                BOOL := 'TRUE';
                MESSAGE := 'SE HA ACTUALIZADO LA INFORMACIÓN DE LA HERRAMIENTA "'||NOMBRE_H||'" EN LA SUCURSAL "'||NOMBRE_S||'"';
            ELSE
                BOOL := 'FALSE';
                MESSAGE := 'LA HERRAMIENTA, SUCURSAL O EMPRESA INGRESADA NO EXISTEN.';
            END IF;
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'EL PRECIO NO PUEDE SER UN VALOR NEGATIVO';
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL STOCK NO PUEDE SER CERO O NEGATIVO';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR INFORMACIÓN DE LAS HERRAMIENTAS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END; 
$$;


ALTER FUNCTION public.actualizar_h_sucursal(codigo integer, codigo_s integer, stock_h integer, precio_h integer, empresa_h integer, descuento_h integer, inicio_h date, fin_h date, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_herramienta(integer, character varying, character varying, character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_herramienta(codigo integer, nombre_h character varying, descripcion_h character varying, url_foto_h character varying, categoria_h integer, empresa_h integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
    NOMBRE_HH VARCHAR(100);
BEGIN
    IF(CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_HH FROM HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND EMPRESA = EMPRESA_H;
        LOCK TABLE HERRAMIENTA IN ROW EXCLUSIVE MODE;
        UPDATE HERRAMIENTA
        SET NOMBRE=NOMBRE_H,DESCRIPCION=DESCRIPCION_H,COD_CATEGORIA=CATEGORIA_H
        WHERE COD_HERRAMIENTA=CODIGO AND EMPRESA = EMPRESA_H;        
        BOOL := 'TRUE';
        MESSAGE := 'SE HA ACTUALIZADO EXITOSAMENTE LA HERRAMIENTA CÓDIGO: '||NOMBRE_HH;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'NO SE PUEDE ACTUALIZAR LA HERRAMIENTA YA QUE NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR LA HERRAMIENTA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_herramienta(codigo integer, nombre_h character varying, descripcion_h character varying, url_foto_h character varying, categoria_h integer, empresa_h integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_password_administrador(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_password_administrador(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    NOMBRE_USER VARCHAR(100);
    L_SAL VARCHAR(30) := 'CONSTRUOK-PIPEMAX-1994';
BEGIN
    IF (CHECKADMIN(RUT_U)=TRUE)  THEN
        SELECT CONCAT(CONCAT(NOMBRES,' '),APELLIDOS) INTO NOMBRE_USER FROM ADMINISTRADOR WHERE RUT = RUT_U;
        LOCK TABLE ADMINISTRADOR IN ROW EXCLUSIVE MODE;
        UPDATE ADMINISTRADOR
        SET PASS = CRYPT(PASS_U, L_SAL)
        WHERE RUT=RUT_U;
        BOOL := 'TRUE';
        MESSAGE := 'LA CONTRASEÑA DEL USUARIO: '||NOMBRE_USER||' SE HA MODIFICADO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA CONTRASEÑA NO SE PUDO MODIFICAR YA QUE EL USUARIO NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA MODIFICAR LA CONTRASEÑA DE ESTE USUARIO';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_password_administrador(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_password_user(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_password_user(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    NOMBRE_USER VARCHAR(100);
    L_SAL VARCHAR(30) := 'CONSTRUOK-PIPEMAX-1994';
BEGIN
    IF (CHECKUSER(RUT_U)=TRUE)  THEN
        SELECT CONCAT(CONCAT(NOMBRES,' '),APELLIDOS) INTO NOMBRE_USER FROM USUARIO WHERE RUT = RUT_U;
        LOCK TABLE USUARIO IN ROW EXCLUSIVE MODE;
        UPDATE USUARIO
        SET PASS = CRYPT(UPPER(PASS_U), L_SAL)
        WHERE RUT=RUT_U;
        BOOL := 'TRUE';
        MESSAGE := 'LA CONTRASEÑA DEL USUARIO: '||NOMBRE_USER||' SE HA MODIFICADO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA CONTRASEÑA NO SE PUDO MODIFICAR YA QUE EL USUARIO NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA MODIFICAR LA CONTRASEÑA DE ESTE USUARIO';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_password_user(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_password_user_verificar(integer, character varying, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_password_user_verificar(rut_u integer, pass_u character varying, new_pass character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    L_SAL VARCHAR(30) := 'CONSTRUOK-PIPEMAX-1994';
BEGIN
    IF CHECKUSER(RUT_U)=TRUE THEN
        IF (VALIDAR_LOGIN(RUT_U,PASS_U)=TRUE)  THEN
            LOCK TABLE USUARIO IN ROW EXCLUSIVE MODE;
            UPDATE USUARIO
            SET PASS = CRYPT(UPPER(NEW_PASS), L_SAL)
            WHERE RUT=RUT_U;
            BOOL := 'TRUE';
            MESSAGE := 'LA CONTRASEÑA SE HA ACTUALIZADO EXITOSAMENTE';
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'LA CONTRASEÑA ANTIGUA INGRESADA NO ES CORRECTA';
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA CONTRASEÑA NO SE PUDO MODIFICAR YA QUE EL USUARIO NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA MODIFICAR LA CONTRASEÑA DE ESTE USUARIO';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_password_user_verificar(rut_u integer, pass_u character varying, new_pass character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_sucursal(integer, character varying, character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_sucursal(codigo integer, nombre_s character varying, direccion_s character varying, telefono_s integer, cod_empresa_s integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_SUCURSAL VARCHAR(100);
BEGIN 
    IF(CHECKSUCURSAL(CODIGO,COD_EMPRESA_S)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_SUCURSAL FROM SUCURSAL WHERE COD_SUCURSAL = CODIGO AND COD_EMPRESA = COD_EMPRESA_S;
        LOCK TABLE SUCURSAL IN ROW EXCLUSIVE MODE;
        UPDATE SUCURSAL
        SET NOMBRE=NOMBRE_S,DIRECCION=DIRECCION_S,TELEFONO=TELEFONO_S
        WHERE COD_SUCURSAL=CODIGO
        AND COD_EMPRESA = COD_EMPRESA_S;        
        BOOL := 'TRUE';
        MESSAGE := 'LA ACTUALIZACIÓN DE LA SUCURSAL: '||NOMBRE_SUCURSAL||' SE HA REALIZADO CON ÉXITO';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'NO SE PUDO ACTUALIZAR LA SUCURSAL YA QUE NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR LA SUCURSAL';
            RETURN;
        WHEN OTHERS THEN    
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_sucursal(codigo integer, nombre_s character varying, direccion_s character varying, telefono_s integer, cod_empresa_s integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: actualizar_user(integer, character varying, character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.actualizar_user(rut_a integer, nombres_a character varying, apellidos_a character varying, correo_a character varying, direccion_u character varying, celular_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    NOMBRE_USER VARCHAR(100);
BEGIN
    IF(CHECKUSER(RUT_A)=TRUE) THEN
        SELECT CONCAT(CONCAT(NOMBRES,' '),APELLIDOS) INTO NOMBRE_USER FROM USUARIO WHERE RUT = RUT_A;
        LOCK TABLE USUARIO IN ROW EXCLUSIVE MODE;
        UPDATE USUARIO
        SET NOMBRES=NOMBRES_A,APELLIDOS=APELLIDOS_A,CORREO=CORREO_A,DIRECCION=DIRECCION_U,CELULAR=CELULAR_U
        WHERE RUT=RUT_A;
        BOOL := 'TRUE';
        MESSAGE := 'EL USUARIO: '||NOMBRE_USER||' HA SIDO MODIFICADO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL USUARIO QUE INTENTA MODIFICAR NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA MODIFICAR USUARIOS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.actualizar_user(rut_a integer, nombres_a character varying, apellidos_a character varying, correo_a character varying, direccion_u character varying, celular_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: agrega_carrito(integer, integer, integer, integer, integer, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.agrega_carrito(rut_u integer, codigo integer, codigo_s integer, empresa_h integer, comuna_h integer, fecha_i character varying, fecha_f character varying, cantidad_c integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    MAXIMOSTOCK INT;
    CANTIDAD_CARRO INT;
BEGIN    
    IF(CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE) THEN
        MAXIMOSTOCK := CHECKSTOCK(CODIGO,CODIGO_S,EMPRESA_H,COMUNA_H,FECHA_I,FECHA_F);    
        IF(CHECKCARRITO(CODIGO,CODIGO_S,RUT_U,EMPRESA_H)=TRUE) THEN
            IF(CANTIDAD_C>0) THEN                
                CANTIDAD_CARRO := CHECK_CANTIDAD_CARRITO(CODIGO,CODIGO_S,RUT_U,EMPRESA_H); 
                IF (CANTIDAD_CARRO<>-1) THEN
                    IF((CANTIDAD_C + CANTIDAD_CARRO)<=MAXIMOSTOCK) THEN
                        LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
                        UPDATE CARRITO
                        SET CANTIDAD = CANTIDAD_CARRO + CANTIDAD_C
                        WHERE COD_HERRAMIENTA = CODIGO
                        AND RUT = RUT_U
                        AND COD_SUCURSAL = CODIGO_S
                        AND EMPRESA = EMPRESA_H;                        
                        BOOL := 'TRUE';
                        MESSAGE := 'EL CARRITO SE HA ACTUALIZADO CON ÉXITO';
                    ELSE
                        IF(CANTIDAD_CARRO = MAXIMOSTOCK) THEN
                            BOOL := 'FALSE';
                            MESSAGE := 'YA ALCANZÓ EL MÁXIMO STOCK DE NUESTRA HERRAMIENTA EN EL CARRITO';
                        ELSE
                            LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
                            UPDATE CARRITO
                            SET CANTIDAD = MAXIMOSTOCK
                            WHERE COD_HERRAMIENTA = CODIGO
                            AND RUT = RUT_U
                            AND COD_SUCURSAL = CODIGO_S
                            AND EMPRESA = EMPRESA_H;                            
                            BOOL := 'TRUE';
                            MESSAGE := 'EL CARRITO SE HA ACTUALIZADO CON ÉXITO';
                        END IF;
                    END IF;
                ELSE
                    BOOL := 'FALSE';
                    MESSAGE := 'LA HERRAMIENTA QUE SELECCIONÓ NO EXISTE EN LA BASE DE DATOS';
                END IF;
            ELSE
                BOOL := 'FALSE';
                MESSAGE := 'LA CANTIDAD DEL PRODUCTO NO PUEDE SER CERO O NEGATIVA';
            END IF;
        ELSE
            IF(CANTIDAD_C>0) THEN
                IF(CANTIDAD_C > MAXIMOSTOCK) THEN
                    BOOL := 'FALSE';
                    MESSAGE := 'LA CANTIDAD DE PRODUCTOS QUE HA SELECCIONADO EXCEDE EL MÁXIMO';
                ELSE
                    LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
                    INSERT INTO CARRITO (COD_HERRAMIENTA,RUT,CANTIDAD,COD_SUCURSAL,ESTADO,EMPRESA) VALUES (CODIGO,RUT_U,CANTIDAD_C,CODIGO_S,1,EMPRESA_H);                    
                    BOOL := 'TRUE';
                    MESSAGE := 'SU CARRITO SE HA ACTUALIZADO CON LOS PRODUCTOS SELECCIONADOS';
                END IF;
            ELSE
                BOOL := 'FALSE';
                MESSAGE := 'LA CANTIDAD DEL PRODUCTO NO PUEDE SER CERO O NEGATIVA';
            END IF;
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA HERRAMIENTA QUE SELECCIONÓ NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
END;
$$;


ALTER FUNCTION public.agrega_carrito(rut_u integer, codigo integer, codigo_s integer, empresa_h integer, comuna_h integer, fecha_i character varying, fecha_f character varying, cantidad_c integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: ajusta_total_carrito_insert(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.ajusta_total_carrito_insert() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    TOTAL_PRODUCTO INT;
    DESCUENTO_PRODUCTO INT;
    TOTAL INT;
BEGIN
    SELECT PRECIO, VERIFICAR_DESCUENTO(COD_HERRAMIENTA,EMPRESA,COD_SUCURSAL) INTO TOTAL_PRODUCTO,DESCUENTO_PRODUCTO FROM SUCURSAL_HERRAMIENTA 
    WHERE COD_HERRAMIENTA = NEW.COD_HERRAMIENTA
    AND COD_SUCURSAL = NEW.COD_SUCURSAL
    AND EMPRESA = NEW.EMPRESA;
    IF DESCUENTO_PRODUCTO IS NULL THEN
        DESCUENTO_PRODUCTO := 0;
    END IF;
    TOTAL := (TOTAL_PRODUCTO*NEW.CANTIDAD);
    NEW.TOTAL := (TOTAL - (TOTAL*DESCUENTO_PRODUCTO)/100);
    NEW.DESCUENTO := DESCUENTO_PRODUCTO;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.ajusta_total_carrito_insert() OWNER TO jefe;

--
-- Name: ajusta_total_carrito_update(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.ajusta_total_carrito_update() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE 
    TOTAL_PRODUCTO INT;
    DESCUENTO_PRODUCTO INT;
    TOTAL INT;
BEGIN
    SELECT PRECIO, VERIFICAR_DESCUENTO(COD_HERRAMIENTA,EMPRESA,COD_SUCURSAL) INTO TOTAL_PRODUCTO, DESCUENTO_PRODUCTO FROM SUCURSAL_HERRAMIENTA 
    WHERE COD_HERRAMIENTA = NEW.COD_HERRAMIENTA
    AND COD_SUCURSAL = NEW.COD_SUCURSAL
    AND EMPRESA = NEW.EMPRESA;
    IF DESCUENTO_PRODUCTO IS NULL THEN
        DESCUENTO_PRODUCTO := 0;
    END IF;
    TOTAL := (TOTAL_PRODUCTO*NEW.CANTIDAD);
    NEW.TOTAL := (TOTAL - (TOTAL*DESCUENTO_PRODUCTO)/100);
    NEW.DESCUENTO := DESCUENTO_PRODUCTO;  
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.ajusta_total_carrito_update() OWNER TO jefe;

--
-- Name: arrendar(character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.arrendar(f_inicio character varying, f_final character varying, rut_u integer, OUT bool character varying, OUT message character varying, OUT codigo_arriendo integer) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    VERIFICADOR INT;
    CONTADOR INT;
    ALMACENAJE INT;
    ID_ARRIENDO INT;
    REGISTRO RECORD;
    DIFERENCIA INT := 0;
    VERIFICADOR_CURSOR INT := 0;
    VERIFICA_PRODUCTOS CURSOR FOR SELECT COD_HERRAMIENTA,CANTIDAD,COD_SUCURSAL,TOTAL,EMPRESA,DESCUENTO FROM CARRITO 
    WHERE RUT = RUT_U;
BEGIN
    IF (CHECK_FECHA_FINAL(TO_DATE(F_INICIO,'DD/MM/YYYY'),TO_DATE(F_FINAL,'DD/MM/YYYY'))) = TRUE THEN
        SELECT COUNT(*) INTO VERIFICADOR FROM CARRITO WHERE RUT = RUT_U;   
        CONTADOR := 0;
        FOR REGISTRO IN VERIFICA_PRODUCTOS LOOP        
            VERIFICADOR_CURSOR := 1;
            ALMACENAJE := VERIFICAR_PRODUCTO_VENTA(TO_DATE(F_INICIO,'DD/MM/YYYY'),TO_DATE(F_FINAL,'DD/MM/YYYY'),REGISTRO.COD_SUCURSAL,REGISTRO.COD_HERRAMIENTA,REGISTRO.EMPRESA);  
            IF REGISTRO.CANTIDAD <= ALMACENAJE THEN
                CONTADOR := CONTADOR + 1;
            END IF;
        END LOOP;
        IF VERIFICADOR_CURSOR=0 THEN
            BOOL := 'FALSE';
            MESSAGE := 'EL CARRITO DE COMPRAS ESTÁ VACÍO';
            CODIGO_ARRIENDO := -1;
            RETURN;
        END IF;
        IF CONTADOR = VERIFICADOR THEN
            ID_ARRIENDO := NEXTVAL('ARRIENDO_AI');
            LOCK TABLE ARRIENDO IN ROW EXCLUSIVE MODE;
            INSERT INTO ARRIENDO VALUES (ID_ARRIENDO,TO_DATE(F_INICIO,'DD/MM/YYYY'),TO_DATE(F_FINAL,'DD/MM/YYYY'),0,RUT_U,'PENDIENTE',NOW());
            LOCK TABLE DETALLE IN ROW EXCLUSIVE MODE;
            FOR REGISTRO IN VERIFICA_PRODUCTOS LOOP
                INSERT INTO DETALLE VALUES (REGISTRO.COD_HERRAMIENTA,REGISTRO.CANTIDAD,REGISTRO.TOTAL,ID_ARRIENDO,REGISTRO.EMPRESA,'PENDIENTE',REGISTRO.COD_SUCURSAL,(REGISTRO.TOTAL/REGISTRO.CANTIDAD),REGISTRO.DESCUENTO);
            END LOOP;            
            IF VACIAR_CARRO(RUT_U) = TRUE THEN
                SELECT DATE_PART('day',AGE(TO_DATE(F_FINAL,'DD/MM/YYYY'),TO_DATE(F_INICIO,'DD/MM/YYYY'))) INTO DIFERENCIA;
                UPDATE ARRIENDO
                SET TOTAL = TOTAL * DIFERENCIA
                WHERE COD_ARRIENDO = ID_ARRIENDO;
                BOOL := 'TRUE';
                MESSAGE := 'EL ARRIENDO SE HA REALIZADO EXITOSAMENTE';
                CODIGO_ARRIENDO := ID_ARRIENDO;
            ELSE
                BOOL := 'TRUE';
                MESSAGE := 'EL ARRIENDO SE HA REALIZADO EXITOSAMENTE, PERO FALLÓ LA LIMPIEZA DEL CARRO DE COMPRAS';
                CODIGO_ARRIENDO := ID_ARRIENDO;
            END IF;
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'UNA O MÁS HERRAMIENTAS DE SU CARRITO YA HAN SIDO ARRENDADAS POR OTRO USUARIO. PRUEBE LIMPIANDO EL CARRO DE ARRIENDOS Y SELECCIONE NUEVAMENTE LAS HERRAMIENTAS';
            CODIGO_ARRIENDO := -1;
        END IF;
        
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA FECHA FINAL DEBE SER A LO MENOS EL DÍA SIGUIENTE DE LA FECHA INICIAL';
        CODIGO_ARRIENDO := -1;
        RETURN;
    END IF;
    RETURN;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO HAY PRODUCTOS EN EL CARRITO';
            CODIGO_ARRIENDO := -1;
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            CODIGO_ARRIENDO := -1;
            RETURN;
END;
$$;


ALTER FUNCTION public.arrendar(f_inicio character varying, f_final character varying, rut_u integer, OUT bool character varying, OUT message character varying, OUT codigo_arriendo integer) OWNER TO jefe;

--
-- Name: borrar_herramienta_carrito(integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.borrar_herramienta_carrito(rut_u integer, codigo_h integer, codigo_s integer, empresa_h integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
BEGIN
    IF(CHECKCARRITO(CODIGO_H,CODIGO_S,RUT_U,EMPRESA_H)=TRUE) THEN
        LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
        DELETE FROM CARRITO WHERE COD_HERRAMIENTA = CODIGO_H
        AND COD_SUCURSAL = CODIGO_S
        AND RUT = RUT_U
        AND EMPRESA = EMPRESA_H;        
        BOOL := 'TRUE';
        MESSAGE := 'LA HERRAMIENTA SE HA ELIMINADO CON EXITO';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA HERRAMIENTA NO ESTÁ EN EL CARRITO';
    END IF;
    RETURN;
    EXCEPTION
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.borrar_herramienta_carrito(rut_u integer, codigo_h integer, codigo_s integer, empresa_h integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: calculo_total(date, date, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.calculo_total(fecha_f date, fecha_i date, cantidad integer, total integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    DIFERENCIA INT := 0;
    SUMADOR INT := 0;
BEGIN
    SELECT DATE_PART('day',AGE(FECHA_F,FECHA_I)) INTO DIFERENCIA;
    SUMADOR := (TOTAL*CAST(DIFERENCIA AS INT));
    RETURN SUMADOR;
END;
$$;


ALTER FUNCTION public.calculo_total(fecha_f date, fecha_i date, cantidad integer, total integer) OWNER TO jefe;

--
-- Name: check_cantidad_carrito(integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.check_cantidad_carrito(codigo integer, codigo_s integer, rut_u integer, empresa_h integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    CANTIDAD_CARRO INT;
BEGIN
    SELECT CANTIDAD INTO CANTIDAD_CARRO FROM CARRITO 
    WHERE COD_HERRAMIENTA=CODIGO
    AND COD_SUCURSAL = CODIGO_S
    AND RUT = RUT_U
    AND EMPRESA = EMPRESA_H;
    RETURN CANTIDAD_CARRO;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1;
END;
$$;


ALTER FUNCTION public.check_cantidad_carrito(codigo integer, codigo_s integer, rut_u integer, empresa_h integer) OWNER TO jefe;

--
-- Name: check_fecha_final(date, date); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.check_fecha_final(finicio date, ffinal date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
    IF FFINAL>FINICIO THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END;
$$;


ALTER FUNCTION public.check_fecha_final(finicio date, ffinal date) OWNER TO jefe;

--
-- Name: checkadmin(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkadmin(rut_u integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE 
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM ADMINISTRADOR WHERE RUT=RUT_U;
    IF(CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.checkadmin(rut_u integer) OWNER TO jefe;

--
-- Name: checkcarrito(integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkcarrito(codigo integer, codigo_s integer, rut_u integer, empresa_h integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM CARRITO 
    WHERE COD_HERRAMIENTA=CODIGO
    AND COD_SUCURSAL = CODIGO_S
    AND RUT = RUT_U
    AND EMPRESA = EMPRESA_H;
    IF(CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.checkcarrito(codigo integer, codigo_s integer, rut_u integer, empresa_h integer) OWNER TO jefe;

--
-- Name: checkcategoria(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkcategoria(codigo integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM CATEGORIA WHERE COD_CATEGORIA=CODIGO;
    IF(CONTADOR>0) THEN
        RETURN TRUE;
    ELSE 
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.checkcategoria(codigo integer) OWNER TO jefe;

--
-- Name: checkempresa(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkempresa(codigo integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    CONTADOR INT;
BEGIN 
    SELECT COUNT(*) INTO CONTADOR FROM EMPRESA WHERE COD_EMPRESA=CODIGO;
    IF (CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;    
$$;


ALTER FUNCTION public.checkempresa(codigo integer) OWNER TO jefe;

--
-- Name: checkherramienta(integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkherramienta(codigo integer, empresa_h integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM HERRAMIENTA 
    WHERE COD_HERRAMIENTA=CODIGO
    AND EMPRESA = EMPRESA_H;
    IF(CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.checkherramienta(codigo integer, empresa_h integer) OWNER TO jefe;

--
-- Name: checkstock(integer, integer, integer, integer, character varying, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkstock(codigo integer, codigo_s integer, empresa_h integer, comuna_h integer, fecha_i character varying, fecha_f character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    CANTIDAD INT;
BEGIN    
    SELECT TEMPORAL.STOCK INTO CANTIDAD FROM
    (SELECT (SH.STOCK - SUM(D.CANTIDAD)) AS STOCK
    FROM HERRAMIENTA H JOIN CATEGORIA C 
    ON H.COD_CATEGORIA = C.COD_CATEGORIA
    JOIN SUCURSAL_HERRAMIENTA SH
    ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
    AND H.EMPRESA = SH.EMPRESA
    JOIN SUCURSAL SU
    ON SH.COD_SUCURSAL = SU.COD_SUCURSAL
    JOIN DETALLE D 
    ON D.COD_H = SH.COD_HERRAMIENTA
    AND D.EMPRESA = SH.EMPRESA
    AND D.COD_SUCURSAL = SH.COD_SUCURSAL
    JOIN EMPRESA E
    ON E.COD_EMPRESA = SU.COD_EMPRESA
    WHERE D.ID_A IN (SELECT A.COD_ARRIENDO FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO = D.ID_A JOIN SUCURSAL_HERRAMIENTA H
                                    ON D.COD_H = H.COD_HERRAMIENTA
                                    AND D.EMPRESA = H.EMPRESA
                                    AND D.COD_SUCURSAL = H.COD_SUCURSAL
                                    WHERE A.FECHA_INICIO BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                    OR A.FECHA_FINAL BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                    AND H.COD_HERRAMIENTA = CODIGO
                                    GROUP BY A.COD_ARRIENDO)
    AND SU.COMUNA = COMUNA_H
    AND SH.COD_HERRAMIENTA = CODIGO
    AND SH.COD_SUCURSAL = CODIGO_S
    GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, H.EMPRESA, E.NOMBRE, SU.COD_SUCURSAL, SU.NOMBRE, SH.STOCK   
    UNION 
    SELECT SH.STOCK AS STOCK
    FROM HERRAMIENTA H JOIN CATEGORIA C 
    ON H.COD_CATEGORIA = C.COD_CATEGORIA
    JOIN SUCURSAL_HERRAMIENTA SH
    ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
    AND H.EMPRESA = SH.EMPRESA
    JOIN SUCURSAL SU
    ON SH.COD_SUCURSAL = SU.COD_SUCURSAL
    FULL OUTER JOIN DETALLE D 
    ON D.COD_H = SH.COD_HERRAMIENTA
    AND D.EMPRESA = SH.EMPRESA
    AND D.COD_SUCURSAL = SH.COD_SUCURSAL
    JOIN EMPRESA E
    ON E.COD_EMPRESA = SU.COD_EMPRESA
    WHERE (H.COD_HERRAMIENTA,SH.COD_SUCURSAL) NOT IN (SELECT H.COD_HERRAMIENTA,H.COD_SUCURSAL FROM ARRIENDO A JOIN DETALLE D 
                                ON A.COD_ARRIENDO = D.ID_A JOIN SUCURSAL_HERRAMIENTA H
                                ON D.COD_H = H.COD_HERRAMIENTA
                                AND D.EMPRESA = H.EMPRESA
                                AND D.COD_SUCURSAL = H.COD_SUCURSAL
                                WHERE A.FECHA_INICIO BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                OR A.FECHA_FINAL BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                AND H.COD_HERRAMIENTA = CODIGO)
    AND SU.COMUNA = COMUNA_H
    AND SH.COD_HERRAMIENTA = CODIGO
    AND SH.COD_SUCURSAL = CODIGO_S) AS TEMPORAL;
    RETURN CANTIDAD;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN            
            SELECT STOCK INTO CANTIDAD FROM SUCURSAL_HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND COD_SUCURSAL = CODIGO_S AND EMPRESA = EMPRESA_H;
            RETURN CANTIDAD;
END;
$$;


ALTER FUNCTION public.checkstock(codigo integer, codigo_s integer, empresa_h integer, comuna_h integer, fecha_i character varying, fecha_f character varying) OWNER TO jefe;

--
-- Name: checksucursal(integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checksucursal(codigo integer, empresa_s integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    CONTADOR INT;
BEGIN 
    SELECT COUNT(*) INTO CONTADOR FROM SUCURSAL WHERE COD_SUCURSAL=CODIGO AND COD_EMPRESA = EMPRESA_S;
    IF (CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;    
$$;


ALTER FUNCTION public.checksucursal(codigo integer, empresa_s integer) OWNER TO jefe;

--
-- Name: checkuser(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.checkuser(rut_u integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE 
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM USUARIO WHERE RUT=RUT_U;
    IF(CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.checkuser(rut_u integer) OWNER TO jefe;

--
-- Name: descuento_total(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.descuento_total() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    VERIFICADOR INT;
BEGIN
    SELECT COUNT(*) INTO VERIFICADOR FROM CARRITO
    WHERE COD_HERRAMIENTA = NEW.COD_HERRAMIENTA AND COD_SUCURSAL = NEW.COD_SUCURSAL AND EMPRESA = NEW.EMPRESA;
    IF VERIFICADOR>0 THEN
        UPDATE CARRITO
        SET DESCUENTO = VERIFICAR_DESCUENTO(COD_HERRAMIENTA,EMPRESA,COD_SUCURSAL)
        WHERE COD_HERRAMIENTA = NEW.COD_HERRAMIENTA AND COD_SUCURSAL = NEW.COD_SUCURSAL AND EMPRESA = NEW.EMPRESA;
    END IF;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.descuento_total() OWNER TO jefe;

--
-- Name: desvincular_h_sucursal(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.desvincular_h_sucursal(codigo integer, codigo_s integer, empresa_h integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_S VARCHAR(100);
    NOMBRE_H VARCHAR(100);
BEGIN
    IF (CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE AND CHECKSUCURSAL(CODIGO_S,EMPRESA_H)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_S FROM SUCURSAL WHERE COD_SUCURSAL = CODIGO_S AND COD_EMPRESA = EMPRESA_H;
        SELECT NOMBRE INTO NOMBRE_H FROM HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND EMPRESA = EMPRESA_H;
        IF(VERIFICA_HERRAMIENTA_SUCURSAL(CODIGO,CODIGO_S,EMPRESA_H)=TRUE) THEN
            LOCK TABLE SUCURSAL_HERRAMIENTA IN ROW EXCLUSIVE MODE;
            DELETE FROM SUCURSAL_HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND COD_SUCURSAL = CODIGO_S AND EMPRESA = EMPRESA_H;
            
            BOOL := 'TRUE';
            MESSAGE := 'LA HERRAMIENTA: "'||NOMBRE_H||'" SE HA DESVINCULADO DE LA SUCURSAL: "'||NOMBRE_S||'"';
        END IF;            
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL CÓDIGO DE HERRAMIENTA O LA SUCURSAL SELECCIONADA NO EXISTE.';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA DESVINCULAR HERRAMIENTAS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;            
END; 
$$;


ALTER FUNCTION public.desvincular_h_sucursal(codigo integer, codigo_s integer, empresa_h integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: eliminar_administrador(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.eliminar_administrador(rut_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    NOMBRE_USER VARCHAR(100);
BEGIN
    IF(CHECKADMIN(RUT_U)=TRUE) THEN
        SELECT CONCAT(CONCAT(NOMBRES,' '),APELLIDOS) INTO NOMBRE_USER FROM ADMINISTRADOR WHERE RUT = RUT_U;
        LOCK TABLE ADMINISTRADOR IN ROW EXCLUSIVE MODE;
        DELETE FROM ADMINISTRADOR WHERE RUT=RUT_U;
        BOOL := 'TRUE';
        MESSAGE := 'EL USUARIO: '||NOMBRE_USER||' FUE ELIMINADO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
	MESSAGE := 'EL USUARIO INGRESADO NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA ELIMINAR USUARIOS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
	    RETURN;
END;
$$;


ALTER FUNCTION public.eliminar_administrador(rut_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: eliminar_categoria(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.eliminar_categoria(codigo integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ DECLARE
BEGIN 
    IF(CHECKCATEGORIA(CODIGO)=TRUE) THEN
        LOCK TABLE CATEGORIA IN ROW EXCLUSIVE MODE;
        DELETE FROM CATEGORIA WHERE COD_CATEGORIA=CODIGO;        
        BOOL := 'TRUE';
        MESSAGE := 'SE HA ELIMINADO LA CATEGORIA EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA CATEGORIA QUE INTENTA ELIMINAR NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
        WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA ELIMINAR LA CATEGORIA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.eliminar_categoria(codigo integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: eliminar_empresa(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.eliminar_empresa(codigo integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_EMPRESA VARCHAR(100);
BEGIN
    IF(CHECKEMPRESA(CODIGO)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_EMPRESA FROM EMPRESA WHERE COD_EMPRESA = CODIGO;
        LOCK TABLE EMPRESA IN ROW EXCLUSIVE MODE;
        DELETE FROM EMPRESA WHERE COD_EMPRESA=CODIGO;
        
        BOOL := 'TRUE';
        MESSAGE := 'LA EMPRESA: '||NOMBRE_EMPRESA||' HA SIDO ELIMINADA SATISFACTORIAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA EMPRESA QUE INTENTA ELIMINAR NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ELIMINAR UNA EMPRESA';
            RETURN;
        WHEN OTHERS THEN
            BOOL:='FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.eliminar_empresa(codigo integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: eliminar_herramienta(integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.eliminar_herramienta(codigo integer, empresa_h integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
BEGIN
    IF(CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE) THEN
        LOCK TABLE HERRAMIENTA IN ROW EXCLUSIVE MODE;
        DELETE FROM HERRAMIENTA WHERE COD_HERRAMIENTA=CODIGO AND EMPRESA = EMPRESA_H;
        
        BOOL := 'TRUE';
        MESSAGE := 'LA HERRAMIENTA CÓDIGO: '||CODIGO||' HA SIDO ELIMINADA CON ÉXTIO';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA HERRAMIENTA QUE INTENTA ELIMINAR NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ELIMINAR LA HERRAMIENTA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END; 
$$;


ALTER FUNCTION public.eliminar_herramienta(codigo integer, empresa_h integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: eliminar_sucursal(integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.eliminar_sucursal(codigo integer, empresa_s integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_SUCURSAL VARCHAR(100);
BEGIN
    IF(CHECKSUCURSAL(CODIGO,EMPRESA_S)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_SUCURSAL FROM SUCURSAL WHERE COD_SUCURSAL = CODIGO AND COD_EMPRESA = EMPRESA_S;
        LOCK TABLE SUCURSAL IN ROW EXCLUSIVE MODE;
        DELETE FROM SUCURSAL WHERE COD_SUCURSAL=CODIGO AND COD_EMPRESA = EMPRESA_S;
        
        BOOL := 'TRUE';
        MESSAGE := 'LA SUCURSAL: '||NOMBRE_SUCURSAL||' HA SIDO ELIMINADA SATISFACTORIAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA SUCURSAL QUE INTENTA ELIMINAR NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ELIMINAR UNA SUCURSAL';
            RETURN;
        WHEN OTHERS THEN
            BOOL:='FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.eliminar_sucursal(codigo integer, empresa_s integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: eliminar_usuario(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.eliminar_usuario(rut_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    NOMBRE_USER VARCHAR(100);
BEGIN
    IF(CHECKUSER(RUT_U)=TRUE) THEN
        SELECT CONCAT(CONCAT(NOMBRES,' '),APELLIDOS) INTO NOMBRE_USER FROM USUARIO WHERE RUT = RUT_U;
        LOCK TABLE USUARIO IN ROW EXCLUSIVE MODE;
        DELETE FROM USUARIO WHERE RUT=RUT_U;
        BOOL := 'TRUE';
        MESSAGE := 'EL USUARIO: '||NOMBRE_USER||' FUE ELIMINADO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
	MESSAGE := 'EL USUARIO INGRESADO NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA ELIMINAR USUARIOS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
	    RETURN;
END;
$$;


ALTER FUNCTION public.eliminar_usuario(rut_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: getpass(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.getpass(rut_u integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
DECLARE  
    PASS_U VARCHAR(100);      
BEGIN
    IF(CHECKUSER(RUT_U)=TRUE) THEN
        SELECT PASS INTO PASS_U FROM USUARIO WHERE RUT=RUT_U;
        RETURN PASS_U;
    ELSE
        RETURN 'FALSE';
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN 'FALSE';
        WHEN OTHERS THEN 
            RETURN 'FALSE';
END;
$$;


ALTER FUNCTION public.getpass(rut_u integer) OWNER TO jefe;

--
-- Name: getpassadmin(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.getpassadmin(rut_u integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
DECLARE  
    PASS_U VARCHAR(100);      
BEGIN
    IF(CHECKUSER(RUT_U)=TRUE) THEN
        SELECT PASS INTO PASS_U FROM ADMINISTRADOR WHERE RUT=RUT_U;
        RETURN PASS_U;
    ELSE
        RETURN 'FALSE';
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN 'FALSE';
        WHEN OTHERS THEN 
            RETURN 'FALSE';
END;
$$;


ALTER FUNCTION public.getpassadmin(rut_u integer) OWNER TO jefe;

--
-- Name: inicio_sesion(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.inicio_sesion(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ DECLARE
BEGIN
    IF VALIDACION(RUT_U)=TRUE THEN
        IF CHECKUSER(RUT_U)=TRUE THEN
            IF VALIDAR_LOGIN(RUT_U,PASS_U)=TRUE THEN
                BOOL := 'TRUE';
                MESSAGE := 'INICIO DE SESIÓN AUTORIZADO';
            ELSE
                BOOL := 'FALSE';
                MESSAGE := 'LOS DATOS INGRESADOS SON INCORRECTOS';
            END IF;
        ELSE 
            BOOL := 'FALSE';
            MESSAGE := 'EL USUARIO CON RUT: '||RUT_U||' NO EXISTE EN EL SISTEMA';
        END IF;
    ELSE        
        BOOL := 'FALSE';
        MESSAGE := 'EL RUT INGRESADO NO ES VÁLIDO';
    END IF;
    RETURN;
    EXCEPTION
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := 'HA OCURRIDO UN ERROR INTERNO';
            RETURN;
END; 
$$;


ALTER FUNCTION public.inicio_sesion(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: inicio_sesion_admin(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.inicio_sesion_admin(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ DECLARE
BEGIN
    IF VALIDACION(RUT_U)=TRUE THEN
        IF CHECKADMIN(RUT_U)=TRUE THEN
            IF VALIDAR_LOGIN_ADMIN(RUT_U,PASS_U)=TRUE THEN
                BOOL := 'TRUE';
                MESSAGE := 'INICIO DE SESIÓN AUTORIZADO';
            ELSE
                BOOL := 'FALSE';
                MESSAGE := 'LOS DATOS INGRESADOS SON INCORRECTOS';
            END IF;
        ELSE 
            BOOL := 'FALSE';
            MESSAGE := 'EL USUARIO CON RUT: '||RUT_U||' NO EXISTE EN EL SISTEMA';
        END IF;
    ELSE        
        BOOL := 'FALSE';
        MESSAGE := 'EL RUT INGRESADO NO ES VÁLIDO';
    END IF;
    RETURN;
    EXCEPTION
        WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA INICIAR SESION COMO ADMIN';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := 'HA OCURRIDO UN ERROR INTERNO';
            RETURN;
END; 
$$;


ALTER FUNCTION public.inicio_sesion_admin(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: insertar_administrador(integer, character varying, character varying, character varying, character varying, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.insertar_administrador(rut_u integer, nombres_u character varying, apellidos_u character varying, correo_u character varying, pass_u character varying, celular_u integer, empresa_u integer, comuna_u integer, rut_a integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    L_SAL VARCHAR(30) := 'CONSTRUOK-PIPEMAX-1994';
BEGIN
    IF(LENGTH(CAST (RUT_U AS VARCHAR))<10) THEN
        IF(VALIDACION(RUT_U)=TRUE) THEN
            IF(CHECKADMIN(RUT_U)=TRUE) THEN
		BOOL := 'FALSE';
		MESSAGE := 'EL USUARIO CON RUT: '||RUT_U||' QUE INTENTA INGRESAR YA EXISTE';
		RETURN;
	    ELSE
		LOCK TABLE ADMINISTRADOR IN ROW EXCLUSIVE MODE;
		INSERT INTO ADMINISTRADOR VALUES(RUT_U,NOMBRES_U,APELLIDOS_U,CORREO_U,'ADMIN',CRYPT(PASS_U, L_SAL),CELULAR_U,EMPRESA_U,COMUNA_U,RUT_A);
		BOOL := 'TRUE';
		MESSAGE := 'EL USUARIO SE HA INGRESADO EXITOSAMENTE';
		RETURN;
	    END IF;
	ELSE
	    BOOL := 'FALSE';
	    MESSAGE := 'EL RUT: '||RUT_U||' NO ES UN RUT VÁLIDO';
            RETURN;
	END IF;
    ELSE
	BOOL := 'FALSE';
	MESSAGE := 'EL RUT INGRESADO ES MUY LARGO, EXCEDE LOS 10 CARACTERES';
	RETURN;
    END IF;
    EXCEPTION           
	WHEN data_exception THEN
	    BOOL := 'FALSE';
	    MESSAGE := 'HA OCURRIDO UN ERROR DE CONVERSION A NIVEL DE BASE DE DATOS';
            RETURN;
	WHEN unique_violation THEN
	    BOOL := 'FALSE';
	    MESSAGE := 'EL USUARIO CON RUT: '||RUT_U||' QUE INTENTA INGRESAR YA EXISTE';
            RETURN;
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA INGRESAR USUARIOS';
            RETURN;
	WHEN OTHERS THEN
	    BOOL := 'FALSE';
	    MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.insertar_administrador(rut_u integer, nombres_u character varying, apellidos_u character varying, correo_u character varying, pass_u character varying, celular_u integer, empresa_u integer, comuna_u integer, rut_a integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: insertar_usuario(integer, character varying, character varying, character varying, character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.insertar_usuario(rut_u integer, nombres_u character varying, apellidos_u character varying, correo_u character varying, pass_u character varying, direccion_u character varying, celular_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    L_SAL VARCHAR(30) := 'CONSTRUOK-PIPEMAX-1994';
BEGIN
    IF(LENGTH(CAST (RUT_U AS VARCHAR))<10) THEN
        IF(VALIDACION(RUT_U)=TRUE) THEN
            IF(CHECKUSER(RUT_U)=TRUE) THEN
		BOOL := 'FALSE';
		MESSAGE := 'EL USUARIO CON RUT: '||RUT_U||' QUE INTENTA INGRESAR YA EXISTE';
		RETURN;
	    ELSE
		LOCK TABLE USUARIO IN ROW EXCLUSIVE MODE;
		INSERT INTO USUARIO VALUES(RUT_U,NOMBRES_U,APELLIDOS_U,CORREO_U,'CLIENTE',CRYPT(PASS_U, L_SAL),1,DIRECCION_U,CELULAR_U);
		BOOL := 'TRUE';
		MESSAGE := 'EL USUARIO SE HA INGRESADO EXITOSAMENTE';
		RETURN;
	    END IF;
	ELSE
	    BOOL := 'FALSE';
	    MESSAGE := 'EL RUT: '||RUT_U||' NO ES UN RUT VÁLIDO';
            RETURN;
	END IF;
    ELSE
	BOOL := 'FALSE';
	MESSAGE := 'EL RUT INGRESADO ES MUY LARGO, EXCEDE LOS 10 CARACTERES';
	RETURN;
    END IF;
    EXCEPTION           
	WHEN data_exception THEN
	    BOOL := 'FALSE';
	    MESSAGE := 'HA OCURRIDO UN ERROR DE CONVERSION A NIVEL DE BASE DE DATOS';
            RETURN;
	WHEN unique_violation THEN
	    BOOL := 'FALSE';
	    MESSAGE := 'EL USUARIO CON RUT: '||RUT_U||' QUE INTENTA INGRESAR YA EXISTE';
            RETURN;
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA INGRESAR USUARIOS';
            RETURN;
	WHEN OTHERS THEN
	    BOOL := 'FALSE';
	    MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.insertar_usuario(rut_u integer, nombres_u character varying, apellidos_u character varying, correo_u character varying, pass_u character varying, direccion_u character varying, celular_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: isnumeric(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.isnumeric(valor integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
DECLARE 
    RETORNO BOOLEAN;
BEGIN
    SELECT valor = '^[0-9\.]+$' INTO RETORNO;
    RETURN RETORNO;
END;
$_$;


ALTER FUNCTION public.isnumeric(valor integer) OWNER TO jefe;

--
-- Name: isnumeric(text); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.isnumeric(text) RETURNS boolean
    LANGUAGE plpgsql
    AS $_$
DECLARE 
    RETORNO BOOLEAN;
BEGIN
    SELECT '$1' ~ '^[0-9\.]+$' INTO RETORNO;
    RETURN RETORNO;
END;
$_$;


ALTER FUNCTION public.isnumeric(text) OWNER TO jefe;

--
-- Name: mayus_comuna(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.mayus_comuna() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.COMUNA_NOMBRE := INITCAP(NEW.COMUNA_NOMBRE);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.mayus_comuna() OWNER TO jefe;

--
-- Name: mayus_herramienta(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.mayus_herramienta() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.NOMBRE := UPPER(NEW.NOMBRE);
    NEW.DESCRIPCION := UPPER(NEW.DESCRIPCION);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.mayus_herramienta() OWNER TO jefe;

--
-- Name: mayus_provincia(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.mayus_provincia() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.PROVINCIA_NOMBRE := INITCAP(NEW.PROVINCIA_NOMBRE);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.mayus_provincia() OWNER TO jefe;

--
-- Name: mayus_region(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.mayus_region() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.REGION_NOMBRE := INITCAP(NEW.REGION_NOMBRE);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.mayus_region() OWNER TO jefe;

--
-- Name: mayus_sucursal(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.mayus_sucursal() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.NOMBRE := UPPER(NEW.NOMBRE);
    NEW.DIRECCION := UPPER(NEW.DIRECCION);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.mayus_sucursal() OWNER TO jefe;

--
-- Name: mayus_usuario(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.mayus_usuario() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.NOMBRES := UPPER(NEW.NOMBRES);
    NEW.APELLIDOS := UPPER(NEW.APELLIDOS);
    NEW.CORREO := UPPER(NEW.CORREO);
    NEW.DIRECCION := UPPER(NEW.DIRECCION);
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.mayus_usuario() OWNER TO jefe;

--
-- Name: modificar_detalle(integer, integer, integer, integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.modificar_detalle(herramienta_d integer, sucursal_d integer, empresa_d integer, arriendo_d integer, estado_d character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_EMPRESA VARCHAR(100);
BEGIN
    LOCK TABLE DETALLE IN ROW EXCLUSIVE MODE;
    UPDATE DETALLE
    SET ESTADO = ESTADO_D
    WHERE COD_H = HERRAMIENTA_D AND COD_SUCURSAL = SUCURSAL_D
    AND EMPRESA = EMPRESA_D AND ID_A = ARRIENDO_D;
    BOOL := 'TRUE';
    MESSAGE := 'LA MODIFICACIÓN DEL ESTADO SE HA REALIZADO CON ÉXITO';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA ACTUALIZAR LA EMPRESA';
            RETURN;
        WHEN OTHERS THEN    
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.modificar_detalle(herramienta_d integer, sucursal_d integer, empresa_d integer, arriendo_d integer, estado_d character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: modificar_detalle_t(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.modificar_detalle_t() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    ITEMS_DETALLE CURSOR FOR SELECT ESTADO FROM DETALLE 
    WHERE ID_A = NEW.ID_A;
    CONTADOR INT := 0;
    CONTADOR_PENDIENTE INT := 0;
    CONTADOR_ENTREGADO INT := 0;
    CONTADOR_ANULADO INT := 0;
    CONTADOR_COMPLETADO INT := 0;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM DETALLE WHERE ID_A = NEW.ID_A;
    IF OLD.ESTADO<>NEW.ESTADO AND OLD.ESTADO = 'PENDIENTE' AND NEW.ESTADO = 'ANULADO' THEN
        UPDATE ARRIENDO
        SET TOTAL = TOTAL - NEW.TOTAL_DETALLE
        WHERE COD_ARRIENDO = NEW.ID_A;
    ELSIF OLD.ESTADO<>NEW.ESTADO AND OLD.ESTADO = 'ANULADO' THEN
        UPDATE ARRIENDO
        SET TOTAL = TOTAL + NEW.TOTAL_DETALLE
        WHERE COD_ARRIENDO = NEW.ID_A;
    END IF;
    FOR REGISTRO IN ITEMS_DETALLE LOOP  
        IF REGISTRO.ESTADO = 'PENDIENTE' THEN
            CONTADOR_PENDIENTE := CONTADOR_PENDIENTE + 1;            
        ELSIF REGISTRO.ESTADO = 'ENTREGADO' THEN
            CONTADOR_ENTREGADO := CONTADOR_ENTREGADO + 1;
        ELSIF REGISTRO.ESTADO = 'ANULADO' THEN
            CONTADOR_ANULADO := CONTADOR_ANULADO + 1;
        ELSIF REGISTRO.ESTADO = 'COMPLETADO' THEN
            CONTADOR_COMPLETADO := CONTADOR_COMPLETADO + 1;
        END IF;         
    END LOOP;
    IF CONTADOR_PENDIENTE = 1 THEN
        UPDATE ARRIENDO
        SET ESTADO = 'PENDIENTE'
        WHERE COD_ARRIENDO = NEW.ID_A;
    ELSIF CONTADOR_ENTREGADO = CONTADOR THEN
        UPDATE ARRIENDO
        SET ESTADO = 'ENTREGADO'
        WHERE COD_ARRIENDO = NEW.ID_A;
    ELSIF CONTADOR_ANULADO = CONTADOR THEN
        UPDATE ARRIENDO
        SET ESTADO = 'ANULADO'
        WHERE COD_ARRIENDO = NEW.ID_A;
    ELSIF CONTADOR_COMPLETADO = CONTADOR OR (CONTADOR_COMPLETADO + CONTADOR_ANULADO) = CONTADOR THEN
        UPDATE ARRIENDO
        SET ESTADO = 'COMPLETADO'
        WHERE COD_ARRIENDO = NEW.ID_A;
    END IF;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.modificar_detalle_t() OWNER TO jefe;

--
-- Name: nueva_categoria(character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_categoria(nombre_c character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ DECLARE
BEGIN
    LOCK TABLE CATEGORIA IN ROW EXCLUSIVE MODE;
    INSERT INTO CATEGORIA(COD_CATEGORIA,NOMBRE) VALUES (NEXTVAL('CATEGORIA_AI'),NOMBRE_C);    
    BOOL := 'TRUE';
    MESSAGE := 'LA NUEVA CATEGORIA SE HA CREADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
        WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA CREAR UNA CATEGORIA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_categoria(nombre_c character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_empresa(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_empresa(cod_emp integer, nombre_s character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    LOCK TABLE EMPRESA IN ROW EXCLUSIVE MODE;
    INSERT INTO EMPRESA VALUES (COD_EMP,NOMBRE_S);
    BOOL := 'TRUE';
    MESSAGE := 'LA EMPRESA SE HA INGRESADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR UNA NUEVA EMPRESA';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA EMPRESA QUE INTENTA INGRESAR YA SE ENCUENTRA REGISTRADA';         
            RETURN;   
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_empresa(cod_emp integer, nombre_s character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_empresa(integer, character varying, integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_empresa(cod_emp integer, nombre_s character varying, region_s integer, ciudad_s character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    LOCK TABLE EMPRESA IN ROW EXCLUSIVE MODE;
    INSERT INTO EMPRESA VALUES (COD_EMP,NOMBRE_S,REGION_S,CIUDAD_S);
    BOOL := 'TRUE';
    MESSAGE := 'LA EMPRESA SE HA INGRESADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR UNA NUEVA EMPRESA';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA EMPRESA QUE INTENTA INGRESAR YA SE ENCUENTRA REGISTRADA';         
            RETURN;   
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_empresa(cod_emp integer, nombre_s character varying, region_s integer, ciudad_s character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_herramienta(integer, character varying, character varying, character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_herramienta(codigo integer, nombre_h character varying, descripcion_h character varying, url_foto_h character varying, categoria_id_h integer, empresa_h integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
    IF CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=FALSE THEN
        LOCK TABLE HERRAMIENTA IN ROW EXCLUSIVE MODE;
        INSERT INTO HERRAMIENTA VALUES(CODIGO,NOMBRE_H,DESCRIPCION_H,URL_FOTO_H,CATEGORIA_ID_H,EMPRESA_H);
        BOOL := 'TRUE';
        MESSAGE := 'SE HA REGISTRADO UNA NUEVA HERRAMIENTA CON CÓDIGO: '||CODIGO;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA HERRAMIENTA QUE INTENTA REGISTRAR YA EXISTE, MODIFIQUE EL STOCK EN LA HERRAMIENTA CÓDIGO: '||CODIGO;
    END IF;
    RETURN;
    EXCEPTION
        WHEN data_exception THEN
            BOOL := 'FALSE';
            MESSAGE := 'HA OCURRIDO UN ERROR DE CONVERSIÓN A NIVEL DE BASE DE DATOS';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA HERRAMIENTA CON CÓDIGO: '||CODIGO||' YA EXISTE EN LA BASE DE DATOS';
            RETURN;
	WHEN foreign_key_violation THEN
	    BOOL := 'FALSE';
            MESSAGE := 'LA CATEGORÍA SELECCIONADA NO EXISTE';
            RETURN;
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR HERRAMIENTAS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM; 
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_herramienta(codigo integer, nombre_h character varying, descripcion_h character varying, url_foto_h character varying, categoria_id_h integer, empresa_h integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_sucursal(character varying, character varying, integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    LOCK TABLE SUCURSAL IN ROW EXCLUSIVE MODE;
    INSERT INTO SUCURSAL(COD_SUCURSAL,NOMBRE,DIRECCION,TELEFONO,URL_FOTO) VALUES (NEXTVAL('SUCURSAL_AI'),NOMBRE_S,DIRECCION_S,TELEFONO_S,URL_FOTO_S);
    BOOL := 'TRUE';
    MESSAGE := 'LA SUCURSAL SE HA INGRESADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR UNA NUEVA SUCURSAL';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA SUCURSAL QUE INTENTA INGRESAR YA SE ENCUENTRA REGISTRADA';         
            RETURN;   
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_sucursal(character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, cod_empresa_s integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    LOCK TABLE SUCURSAL IN ROW EXCLUSIVE MODE;
    INSERT INTO SUCURSAL(COD_SUCURSAL,NOMBRE,DIRECCION,TELEFONO,URL_FOTO,COD_EMPRESA) VALUES (NEXTVAL('SUCURSAL_AI'),NOMBRE_S,DIRECCION_S,TELEFONO_S,URL_FOTO_S,COD_EMPRESA_S);
    BOOL := 'TRUE';
    MESSAGE := 'LA SUCURSAL SE HA INGRESADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR UNA NUEVA SUCURSAL';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA SUCURSAL QUE INTENTA INGRESAR YA SE ENCUENTRA REGISTRADA';         
            RETURN;   
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, cod_empresa_s integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_sucursal(character varying, character varying, integer, character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, cod_empresa_s integer, comuna_s integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    LOCK TABLE SUCURSAL IN ROW EXCLUSIVE MODE;
    INSERT INTO SUCURSAL(COD_SUCURSAL,NOMBRE,DIRECCION,TELEFONO,URL_FOTO,COD_EMPRESA,COMUNA) VALUES (NEXTVAL('SUCURSAL_AI'),NOMBRE_S,DIRECCION_S,TELEFONO_S,URL_FOTO_S,COD_EMPRESA_S,COMUNA_S);
    BOOL := 'TRUE';
    MESSAGE := 'LA SUCURSAL SE HA INGRESADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR UNA NUEVA SUCURSAL';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA SUCURSAL QUE INTENTA INGRESAR YA SE ENCUENTRA REGISTRADA';         
            RETURN;   
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, cod_empresa_s integer, comuna_s integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: nueva_sucursal(character varying, character varying, integer, character varying, integer, integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, cod_empresa_s integer, region_s integer, ciudad_s character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    LOCK TABLE SUCURSAL IN ROW EXCLUSIVE MODE;
    INSERT INTO SUCURSAL(COD_SUCURSAL,NOMBRE,DIRECCION,TELEFONO,URL_FOTO,COD_EMPRESA,REGION,CIUDAD) VALUES (NEXTVAL('SUCURSAL_AI'),NOMBRE_S,DIRECCION_S,TELEFONO_S,URL_FOTO_S,COD_EMPRESA_S,REGION_S,CIUDAD_S);
    BOOL := 'TRUE';
    MESSAGE := 'LA SUCURSAL SE HA INGRESADO EXITOSAMENTE';
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
	    MESSAGE := 'NO TIENE PRIVILEGIOS PARA REGISTRAR UNA NUEVA SUCURSAL';
            RETURN;
        WHEN unique_violation THEN
            BOOL := 'FALSE';
            MESSAGE := 'LA SUCURSAL QUE INTENTA INGRESAR YA SE ENCUENTRA REGISTRADA';         
            RETURN;   
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.nueva_sucursal(nombre_s character varying, direccion_s character varying, telefono_s integer, url_foto_s character varying, cod_empresa_s integer, region_s integer, ciudad_s character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: obtenerpass(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.obtenerpass(rut_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
    PASS_U VARCHAR(100);
BEGIN
    IF ISNUMERIC(RUT_U) THEN
        PASS_U := GETPASS(RUT_U);
        IF(PASS_U!='FALSE') THEN
            BOOL := 'TRUE';
            MESSAGE := PASS_U;
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'EL USUARIO NO EXISTE';
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL VALOR DEL RUT DEBE SER NUMÉRICO';
    END IF;
    RETURN;
    EXCEPTION
        WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA OBTENER LA CONTRASEÑA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.obtenerpass(rut_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: obtenerpassadmin(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.obtenerpassadmin(rut_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
    PASS_U VARCHAR(100);
BEGIN
    IF ISNUMERIC(RUT_U) THEN
        PASS_U := GETPASSADMIN(RUT_U);
        IF(PASS_U!='FALSE') THEN
            BOOL := 'TRUE';
            MESSAGE := PASS_U;
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'EL USUARIO NO EXISTE';
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL VALOR DEL RUT DEBE SER NUMÉRICO';
    END IF;
    RETURN;
    EXCEPTION
        WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA OBTENER LA CONTRASEÑA';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.obtenerpassadmin(rut_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: quita_carrito(integer, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.quita_carrito(rut_u integer, codigo integer, codigo_s integer, empresa_h integer, cantidad_c integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    CANTIDAD_CARRITO INT;
BEGIN
    IF(CANTIDAD_C>0) THEN
        CANTIDAD_CARRITO := CHECK_CANTIDAD_CARRITO(CODIGO,CODIGO_S,RUT_U,EMPRESA_H);
        IF (CANTIDAD_CARRITO<>-1) THEN
            IF(CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE) THEN
                IF(CHECKCARRITO(CODIGO,CODIGO_S,RUT_U,EMPRESA_H)=TRUE) THEN
                    IF((CANTIDAD_CARRITO-CANTIDAD_C)>=0) THEN
                        LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
                        UPDATE CARRITO
                        SET CANTIDAD = CANTIDAD_CARRITO - CANTIDAD_C
                        WHERE COD_HERRAMIENTA = CODIGO
                        AND RUT = RUT_U
                        AND COD_SUCURSAL = CODIGO_S
                        AND EMPRESA = EMPRESA_H;
                        
                        IF(CHECK_CANTIDAD_CARRITO(CODIGO,CODIGO_S,RUT_U,EMPRESA_H)=0) THEN
                            LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
                            DELETE FROM CARRITO WHERE COD_HERRAMIENTA = CODIGO
                            AND RUT = RUT_U
                            AND COD_SUCURSAL = CODIGO_S
                            AND EMPRESA = EMPRESA_H;                            
                        END IF;
                        BOOL := 'TRUE';
                        MESSAGE := 'EL CARRITO SE HA ACTUALIZADO CON ÉXITO';
                    ELSE
                        BOOL := 'FALSE';
                        MESSAGE := 'LA CANTIDAD INGRESADA SUPERA A LA CANTIDAD DE PRODUCTOS DEL CARRITO';
                    END IF;
                END IF;
            END IF;
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'UPS! LA HERRAMIENTA SE ELIMINÓ DEL CARRITO';
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA CANTIDAD DEL PRODUCTO NO PUEDE SER CERO O NEGATIVA';
    END IF;
    RETURN;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO SE PUEDE QUITAR LA HERRAMIENTA YA QUE NO ESTÁ EN EL CARRITO';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.quita_carrito(rut_u integer, codigo integer, codigo_s integer, empresa_h integer, cantidad_c integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: vaciar_carrito(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.vaciar_carrito(rut_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ DECLARE
BEGIN
    IF(CHECKUSER(RUT_U)=TRUE) THEN
        LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
        DELETE FROM CARRITO WHERE RUT=RUT_U;
        
        BOOL := 'TRUE';
        MESSAGE := 'SE HA VACIADO EL CARRITO EXITOSAMENTE';
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL CARRITO NO SE PUEDO VACIAR YA QUE EL USUARIO NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION 
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.vaciar_carrito(rut_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: vaciar_carro(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.vaciar_carro(rut_u integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
    LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
    DELETE FROM CARRITO WHERE RUT = RUT_U;
    RETURN TRUE;
    EXCEPTION
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.vaciar_carro(rut_u integer) OWNER TO jefe;

--
-- Name: validacion(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.validacion(numerico integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    truerut VARCHAR(9);
    digito INT := 0;
    suma INT := 0;
    digitof INT :=0;
    largo INT;
BEGIN
    largo := LENGTH(CAST(NUMERICO AS VARCHAR));
    IF(largo=8) THEN
        TRUERUT := LPAD(CAST(NUMERICO AS VARCHAR),9,'0'); --agrega ceros al comienzo hasta alcanzar un lenght de 9
    ELSE
        TRUERUT := NUMERICO;
    END IF;
    digito := SUBSTR(TRUERUT,9,1);
    suma := CAST(SUBSTR(TRUERUT,1,1) AS INT) * 3;
    suma := (suma + CAST(SUBSTR(TRUERUT,2,1) AS INT) * 2);
    suma := (suma + CAST(SUBSTR(TRUERUT,3,1) AS INT) * 7);
    suma := (suma + CAST(SUBSTR(TRUERUT,4,1) AS INT) * 6);
    suma := (suma + CAST(SUBSTR(TRUERUT,5,1) AS INT) * 5);
    suma := (suma + CAST(SUBSTR(TRUERUT,6,1) AS INT) * 4);
    suma := (suma + CAST(SUBSTR(TRUERUT,7,1) AS INT) * 3);
    suma := (suma + CAST(SUBSTR(TRUERUT,8,1) AS INT) * 2);
    WHILE (suma > 11) LOOP 
      suma := suma - 11; 
    END LOOP; 
    digitof := (11 - suma);
    IF (digitof=10) THEN
      digitof:= 0;
    END IF;
    IF (digitof=digito) THEN
      RETURN TRUE;
    ELSE
      RETURN FALSE;
    END IF;
END; 
$$;


ALTER FUNCTION public.validacion(numerico integer) OWNER TO jefe;

--
-- Name: validacion(character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.validacion(numerico character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    truerut VARCHAR(9);
    digito INT := 0;
    suma INT := 0;
    digitof INT :=0;
    largo INT;
BEGIN
    largo := LENGTH(NUMERICO);
    IF(largo=8) THEN
        TRUERUT := LPAD(NUMERICO,9,'0'); --agrega ceros al comienzo hasta alcanzar un lenght de 9
    ELSE
        TRUERUT := NUMERICO;
    END IF;
    digito := SUBSTR(TRUERUT,9,1);
    suma := SUBSTR(TRUERUT,1,1) * 3;
    suma := (suma + SUBSTR(TRUERUT,2,1) * 2);
    suma := (suma + SUBSTR(TRUERUT,3,1) * 7);
    suma := (suma + SUBSTR(TRUERUT,4,1) * 6);
    suma := (suma + SUBSTR(TRUERUT,5,1) * 5);
    suma := (suma + SUBSTR(TRUERUT,6,1) * 4);
    suma := (suma + SUBSTR(TRUERUT,7,1) * 3);
    suma := (suma + SUBSTR(TRUERUT,8,1) * 2);
    WHILE (suma > 11) LOOP 
      suma := suma - 11; 
    END LOOP; 
    digitof := (11 - suma);
    IF (digitof=10) THEN
      digitof:= 0;
    END IF;
    IF (digitof=digito) THEN
      RETURN TRUE;
    ELSE
      RETURN FALSE;
    END IF;
END; 
$$;


ALTER FUNCTION public.validacion(numerico character varying) OWNER TO jefe;

--
-- Name: validar_login(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.validar_login(rut_c integer, pass_c character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    VERIFICADOR BOOLEAN;
BEGIN
    SELECT PASS = CRYPT(PASS_C, PASS) INTO VERIFICADOR FROM USUARIO WHERE RUT = RUT_C;
    IF VERIFICADOR = TRUE THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION 
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.validar_login(rut_c integer, pass_c character varying) OWNER TO jefe;

--
-- Name: validar_login(character varying, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.validar_login(usuario character varying, pass_c character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    VERIFICADOR BOOLEAN;
BEGIN
    SELECT PASS = CRYPT(PASS_C, PASS) INTO VERIFICADOR FROM USUARIO WHERE RUT = USUARIO;
    IF VERIFICADOR = TRUE THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION 
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.validar_login(usuario character varying, pass_c character varying) OWNER TO jefe;

--
-- Name: validar_login_admin(integer, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.validar_login_admin(rut_c integer, pass_c character varying) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    VERIFICADOR BOOLEAN;
BEGIN
    SELECT PASS = CRYPT(PASS_C, PASS) INTO VERIFICADOR FROM ADMINISTRADOR WHERE RUT = RUT_C;
    IF VERIFICADOR = TRUE THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
    EXCEPTION 
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$;


ALTER FUNCTION public.validar_login_admin(rut_c integer, pass_c character varying) OWNER TO jefe;

--
-- Name: verifica_herramienta_sucursal(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.verifica_herramienta_sucursal(codigo integer, codigo_s integer, empresa_s integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM SUCURSAL_HERRAMIENTA 
    WHERE COD_HERRAMIENTA = CODIGO 
    AND COD_SUCURSAL = CODIGO_S
    AND EMPRESA = EMPRESA_S;
    IF (CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END;
$$;


ALTER FUNCTION public.verifica_herramienta_sucursal(codigo integer, codigo_s integer, empresa_s integer) OWNER TO jefe;

--
-- Name: verificar_comuna(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.verificar_comuna(rut_u integer, region_d integer, comuna_d integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    COMUNA_CARRO SUCURSAL.COMUNA%TYPE;
    CONTADOR1 INT := 0;
    CONTADOR2 INT := 0;
BEGIN
    SELECT S.COMUNA INTO COMUNA_CARRO FROM CARRITO C JOIN SUCURSAL S
    ON C.COD_SUCURSAL = S.COD_SUCURSAL
    WHERE C.RUT = RUT_U;
    SELECT COUNT(*) INTO CONTADOR1 FROM REGION WHERE REGION_ID = REGION_D;
    SELECT COUNT(*) INTO CONTADOR2 FROM COMUNA WHERE COMUNA_ID = COMUNA_D;
    IF CONTADOR1!=0 THEN
        IF CONTADOR2!=0 THEN            
            IF COMUNA_D!=COMUNA_CARRO THEN
                BOOL := 'TRUE';
                MESSAGE := 'CAMBIO DE COMUNA AUTORIZADO';
            ELSE
                BOOL := 'NULL';
                MESSAGE := 'NULL';
            END IF;
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'LA COMUNA INGRESADA NO EXISTE';
        END IF;
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'LA REGION INGRESADA NO EXISTE';
    END IF;
    RETURN;
    EXCEPTION 
        WHEN OTHERS THEN    
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.verificar_comuna(rut_u integer, region_d integer, comuna_d integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: verificar_descuento(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.verificar_descuento(codigo_h integer, codigo_e integer, codigo_s integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    DESCUENTO_H INT;
BEGIN
    SELECT DESCUENTO INTO DESCUENTO_H
    FROM SUCURSAL_HERRAMIENTA
    WHERE COD_HERRAMIENTA = CODIGO_H 
    AND COD_SUCURSAL = CODIGO_S
    AND EMPRESA = CODIGO_E
    AND NOW() BETWEEN F_INICIO_D AND F_FINAL_D;
    IF DESCUENTO_H IS NULL THEN
        RETURN 0;
    ELSE
        RETURN DESCUENTO_H;
    END IF;
END;
$$;


ALTER FUNCTION public.verificar_descuento(codigo_h integer, codigo_e integer, codigo_s integer) OWNER TO jefe;

--
-- Name: verificar_descuentos(); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.verificar_descuentos() RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
    VERIFICAR CURSOR FOR SELECT COD_HERRAMIENTA,COD_SUCURSAL,STOCK,PRECIO,EMPRESA,DESCUENTO,F_INICIO_D,F_FINAL_D FROM SUCURSAL_HERRAMIENTA; 
    FECHA_SISTEMA DATE := NOW();
BEGIN
    FOR REGISTRO IN VERIFICAR LOOP
        IF(REGISTRO.F_INICIO_D < FECHA_SISTEMA AND FECHA_SISTEMA > REGISTRO.F_FINAL_D) THEN
            LOCK TABLE SUCURSAL_HERRAMIENTA IN ROW EXCLUSIVE MODE;
            UPDATE SUCURSAL_HERRAMIENTA
            SET DESCUENTO = NULL, F_INICIO_D = NULL, F_FINAL_D = NULL
            WHERE COD_HERRAMIENTA = REGISTRO.COD_HERRAMIENTA AND COD_SUCURSAL = REGISTRO.COD_SUCURSAL AND EMPRESA = REGISTRO.EMPRESA;
        END IF;
    END LOOP;        
END;
$$;


ALTER FUNCTION public.verificar_descuentos() OWNER TO jefe;

--
-- Name: verificar_producto_venta(date, date, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.verificar_producto_venta(fecha_i date, fecha_f date, cod_s integer, cod_he integer, empresa_h integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    CANTIDADES INT;
    VERIFICADOR INT;
BEGIN
    CANTIDADES := 0;
    SELECT COUNT(*) INTO VERIFICADOR
    FROM SUCURSAL_HERRAMIENTA
    WHERE COD_HERRAMIENTA = COD_HE
    AND COD_SUCURSAL = COD_S;
    IF VERIFICADOR > 0 THEN    
        SELECT * INTO CANTIDADES FROM
        (SELECT SH.STOCK - SUM(D.CANTIDAD)
        FROM HERRAMIENTA H JOIN CATEGORIA C 
        ON H.COD_CATEGORIA=C.COD_CATEGORIA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
        AND H.EMPRESA = SH.EMPRESA
        JOIN SUCURSAL SU
        ON SH.COD_SUCURSAL = SU.COD_SUCURSAL
        FULL OUTER JOIN DETALLE D 
        ON D.COD_H = SH.COD_HERRAMIENTA
        AND D.EMPRESA = SH.EMPRESA
        AND D.COD_SUCURSAL = SH.COD_SUCURSAL
        JOIN EMPRESA E
        ON E.COD_EMPRESA = SU.COD_EMPRESA
        WHERE D.ID_A IN (SELECT A.COD_ARRIENDO FROM ARRIENDO A JOIN DETALLE D 
                            ON A.COD_ARRIENDO = D.ID_A JOIN SUCURSAL_HERRAMIENTA H
                            ON D.COD_H = H.COD_HERRAMIENTA
                            AND D.EMPRESA = H.EMPRESA
                            AND D.COD_SUCURSAL = H.COD_SUCURSAL
                            WHERE A.FECHA_INICIO BETWEEN FECHA_I AND FECHA_F
                            OR A.FECHA_FINAL BETWEEN FECHA_I AND FECHA_F
                            GROUP BY A.COD_ARRIENDO)
        AND SH.COD_SUCURSAL = COD_S
        AND SH.COD_HERRAMIENTA = COD_HE
        AND SH.EMPRESA = EMPRESA_H
        GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, H.EMPRESA, E.NOMBRE, SU.COD_SUCURSAL, SU.NOMBRE, SH.STOCK
        UNION 
        SELECT SH.STOCK
        FROM HERRAMIENTA H JOIN CATEGORIA C 
        ON H.COD_CATEGORIA = C.COD_CATEGORIA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
        AND H.EMPRESA = SH.EMPRESA
        JOIN SUCURSAL SU
        ON SH.COD_SUCURSAL = SU.COD_SUCURSAL
        FULL OUTER JOIN DETALLE D 
        ON D.COD_H = SH.COD_HERRAMIENTA
        AND D.EMPRESA = SH.EMPRESA
        AND D.COD_SUCURSAL = SH.COD_SUCURSAL
        JOIN EMPRESA E
        ON E.COD_EMPRESA = SU.COD_EMPRESA
        WHERE (H.COD_HERRAMIENTA,SH.COD_SUCURSAL) NOT IN (SELECT H.COD_HERRAMIENTA,H.COD_SUCURSAL FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO = D.ID_A JOIN SUCURSAL_HERRAMIENTA H
                                    ON D.COD_H = H.COD_HERRAMIENTA
                                    AND D.EMPRESA = H.EMPRESA
                                    AND D.COD_SUCURSAL = H.COD_SUCURSAL
                                    WHERE A.FECHA_INICIO BETWEEN FECHA_I AND FECHA_F
                                    OR A.FECHA_FINAL BETWEEN FECHA_I AND FECHA_F)
        AND SH.COD_SUCURSAL = COD_S
        AND SH.COD_HERRAMIENTA = COD_HE)
        AS TEMPORAL;
        RETURN CANTIDADES;
    ELSE
        RETURN -1;
    END IF;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1;
END;
$$;


ALTER FUNCTION public.verificar_producto_venta(fecha_i date, fecha_f date, cod_s integer, cod_he integer, empresa_h integer) OWNER TO jefe;

--
-- Name: verificarut(integer); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.verificarut(rut_u integer, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
BEGIN
    IF(CHECKUSER(RUT_U)=TRUE) THEN
        BOOL := 'TRUE';
        MESSAGE := 'USUARIO VERIFICADO';
    ELSE
        BOOL:='FALSE';
        MESSAGE := 'USUARIO NO ENCONTRADO';
    END IF;
    RETURN;
    EXCEPTION
        WHEN OTHERS THEN
            BOOL := 'ERROR';
            MESSAGE := SQLERRM;
            RETURN;
END;
$$;


ALTER FUNCTION public.verificarut(rut_u integer, OUT bool character varying, OUT message character varying) OWNER TO jefe;

--
-- Name: vincular_herramienta_sucursal(integer, integer, integer, integer, integer, integer, character varying, character varying); Type: FUNCTION; Schema: public; Owner: jefe
--

CREATE FUNCTION public.vincular_herramienta_sucursal(codigo integer, codigo_s integer, precio_h integer, stock_h integer, empresa_h integer, descuento_h integer, inicio_h character varying, fin_h character varying, OUT bool character varying, OUT message character varying) RETURNS record
    LANGUAGE plpgsql
    AS $$ 
DECLARE
    NOMBRE_S VARCHAR(100);
    NOMBRE_H VARCHAR(100);
BEGIN
    IF (CHECKHERRAMIENTA(CODIGO,EMPRESA_H)=TRUE AND CHECKSUCURSAL(CODIGO_S,EMPRESA_H)=TRUE) THEN
        SELECT NOMBRE INTO NOMBRE_S FROM SUCURSAL WHERE COD_SUCURSAL = CODIGO_S AND COD_EMPRESA = EMPRESA_H;
        SELECT NOMBRE INTO NOMBRE_H FROM HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND EMPRESA = EMPRESA_H;
        IF(VERIFICA_HERRAMIENTA_SUCURSAL(CODIGO,CODIGO_S,EMPRESA_H)=FALSE) THEN
            LOCK TABLE SUCURSAL_HERRAMIENTA IN ROW EXCLUSIVE MODE;
            INSERT INTO SUCURSAL_HERRAMIENTA VALUES (CODIGO,CODIGO_S,STOCK_H,PRECIO_H,EMPRESA_H,DESCUENTO_H,TO_DATE(INICIO_H,'DD/MM/YYYY'),TO_DATE(FIN_H,'DD/MM/YYYY'));            
            BOOL := 'TRUE';
            MESSAGE := 'LA VINCULACIÓN DE LA HERRAMIENTA: "'||NOMBRE_H||'" CON LA SUCURSAL:  "'||NOMBRE_S||'" SE REALIZÓ CORRECTAMENTE';
        ELSE
            BOOL := 'FALSE';
            MESSAGE := 'YA EXISTE ESTA HERRAMIENTA VINCULADA A ESTA SUCURSAL, MODIFIQUE STOCK O PRECIO';
        END IF;            
    ELSE
        BOOL := 'FALSE';
        MESSAGE := 'EL CÓDIGO DE HERRAMIENTA O LA SUCURSAL SELECCIONADA NO EXISTE EN LA BASE DE DATOS';
    END IF;
    RETURN;
    EXCEPTION
	WHEN insufficient_privilege THEN
            BOOL := 'FALSE';
            MESSAGE := 'NO TIENE PRIVILEGIOS PARA VINCULAR HERRAMIENTAS';
            RETURN;
        WHEN OTHERS THEN
            BOOL := 'FALSE';
            MESSAGE := SQLERRM;
            RETURN;
END; 
$$;


ALTER FUNCTION public.vincular_herramienta_sucursal(codigo integer, codigo_s integer, precio_h integer, stock_h integer, empresa_h integer, descuento_h integer, inicio_h character varying, fin_h character varying, OUT bool character varying, OUT message character varying) OWNER TO jefe;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: administrador; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.administrador (
    rut integer NOT NULL,
    nombres character varying(40),
    apellidos character varying(40),
    correo character varying(50),
    rol character varying(20),
    pass character varying(100),
    celular integer,
    empresa integer,
    comuna integer,
    rut_a integer
);


ALTER TABLE public.administrador OWNER TO jefe;

--
-- Name: arriendo; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.arriendo (
    cod_arriendo integer NOT NULL,
    fecha_inicio date,
    fecha_final date,
    total integer,
    rut_u integer,
    estado character varying(20),
    fecha_arriendo date
);


ALTER TABLE public.arriendo OWNER TO jefe;

--
-- Name: arriendo_ai; Type: SEQUENCE; Schema: public; Owner: jefe
--

CREATE SEQUENCE public.arriendo_ai
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.arriendo_ai OWNER TO jefe;

--
-- Name: carrito; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.carrito (
    cod_herramienta integer NOT NULL,
    cod_sucursal integer NOT NULL,
    rut integer NOT NULL,
    cantidad integer,
    total integer,
    estado integer,
    empresa integer NOT NULL,
    descuento integer
);


ALTER TABLE public.carrito OWNER TO jefe;

--
-- Name: categoria; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.categoria (
    cod_categoria integer NOT NULL,
    nombre character varying(30)
);


ALTER TABLE public.categoria OWNER TO jefe;

--
-- Name: categoria_ai; Type: SEQUENCE; Schema: public; Owner: jefe
--

CREATE SEQUENCE public.categoria_ai
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categoria_ai OWNER TO jefe;

--
-- Name: comuna; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.comuna (
    comuna_id integer NOT NULL,
    comuna_nombre character varying(50),
    comuna_provincia_id integer
);


ALTER TABLE public.comuna OWNER TO jefe;

--
-- Name: contador; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.contador (
    count bigint
);


ALTER TABLE public.contador OWNER TO jefe;

--
-- Name: detalle; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.detalle (
    cod_h integer NOT NULL,
    cantidad integer,
    total_detalle integer,
    id_a integer NOT NULL,
    empresa integer NOT NULL,
    estado character varying(20),
    cod_sucursal integer NOT NULL,
    total_unitario integer,
    descuento integer
);


ALTER TABLE public.detalle OWNER TO jefe;

--
-- Name: empresa; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.empresa (
    cod_empresa integer NOT NULL,
    nombre character varying(100)
);


ALTER TABLE public.empresa OWNER TO jefe;

--
-- Name: empresa_ai; Type: SEQUENCE; Schema: public; Owner: jefe
--

CREATE SEQUENCE public.empresa_ai
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.empresa_ai OWNER TO jefe;

--
-- Name: herramienta; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.herramienta (
    cod_herramienta integer NOT NULL,
    nombre character varying(100),
    descripcion character varying(200),
    url_foto character varying(100),
    cod_categoria integer,
    empresa integer NOT NULL
);


ALTER TABLE public.herramienta OWNER TO jefe;

--
-- Name: provincia; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.provincia (
    provincia_id integer NOT NULL,
    provincia_nombre character varying(50),
    provincia_region_id integer
);


ALTER TABLE public.provincia OWNER TO jefe;

--
-- Name: region; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.region (
    region_id integer NOT NULL,
    region_nombre character varying(50)
);


ALTER TABLE public.region OWNER TO jefe;

--
-- Name: sucursal; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.sucursal (
    cod_sucursal integer NOT NULL,
    nombre character varying(30),
    direccion character varying(100),
    telefono integer,
    url_foto character varying(100),
    cod_empresa integer,
    comuna integer
);


ALTER TABLE public.sucursal OWNER TO jefe;

--
-- Name: sucursal_ai; Type: SEQUENCE; Schema: public; Owner: jefe
--

CREATE SEQUENCE public.sucursal_ai
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sucursal_ai OWNER TO jefe;

--
-- Name: sucursal_herramienta; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.sucursal_herramienta (
    cod_herramienta integer NOT NULL,
    cod_sucursal integer NOT NULL,
    stock integer,
    precio integer,
    empresa integer NOT NULL,
    descuento integer,
    f_inicio_d date,
    f_final_d date
);


ALTER TABLE public.sucursal_herramienta OWNER TO jefe;

--
-- Name: usuario; Type: TABLE; Schema: public; Owner: jefe
--

CREATE TABLE public.usuario (
    rut integer NOT NULL,
    nombres character varying(40),
    apellidos character varying(40),
    correo character varying(50),
    rol character varying(20),
    pass character varying(100),
    estado integer,
    direccion character varying(50),
    celular integer
);


ALTER TABLE public.usuario OWNER TO jefe;

--
-- Data for Name: administrador; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.administrador (rut, nombres, apellidos, correo, rol, pass, celular, empresa, comuna, rut_a) FROM stdin;
86931133	Juan	Tapia	juan@ucm.cl	ADMIN	COaFstREeQwVg	987654327	96792430	7101	183441396
183441396	Claudia	Rojas	crojas@ucm.cl	ADMIN	COaFstREeQwVg	987654322	96792430	7401	185756203
185756203	felipe maximiliano	tapia gómez	ftapia46@gmail.com	SA	COr8Z9S1OHXNw	986520662	96792430	7101	185756203
\.


--
-- Data for Name: arriendo; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.arriendo (cod_arriendo, fecha_inicio, fecha_final, total, rut_u, estado, fecha_arriendo) FROM stdin;
28	2018-11-18	2018-11-19	28970	185756203	PENDIENTE	2018-11-18
29	2018-11-18	2018-11-19	18600	185756203	PENDIENTE	2018-11-18
30	2018-11-28	2018-11-29	21585	185756203	PENDIENTE	2018-11-29
31	2018-11-28	2018-11-29	41368	185756203	PENDIENTE	2018-11-29
33	2018-12-02	2018-12-03	5090	185756203	COMPLETADO	2018-12-02
32	2018-11-30	2018-12-01	5090	185756203	COMPLETADO	2018-11-30
\.


--
-- Data for Name: carrito; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.carrito (cod_herramienta, cod_sucursal, rut, cantidad, total, estado, empresa, descuento) FROM stdin;
\.


--
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.categoria (cod_categoria, nombre) FROM stdin;
1	CARPINTERIA
2	COMPACTACION
3	OBRA GRUESA
4	DEMOLICION
5	ASEO
\.


--
-- Data for Name: comuna; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.comuna (comuna_id, comuna_nombre, comuna_provincia_id) FROM stdin;
1101	Iquique	11
1107	Alto Hospicio	11
1401	Pozo Almonte	14
1402	Camiña	14
1403	Colchane	14
1404	Huara	14
1405	Pica	14
2101	Antofagasta	21
2102	Mejillones	21
2103	Sierra Gorda	21
2104	Taltal	21
2201	Calama	22
2202	Ollagüe	22
2203	San Pedro De Atacama	22
2301	Tocopilla	23
2302	María Elena	23
3101	Copiapó	31
3102	Caldera	31
3103	Tierra Amarilla	31
3201	Chañaral	32
3202	Diego De Almagro	32
3301	Vallenar	33
3302	Alto Del Carmen	33
3303	Freirina	33
3304	Huasco	33
4101	La Serena	41
4102	Coquimbo	41
4103	Andacollo	41
4104	La Higuera	41
4105	Paihuano	41
4106	Vicuña	41
4201	Illapel	42
4202	Canela	42
4203	Los Vilos	42
4204	Salamanca	42
4301	Ovalle	43
4302	Combarbalá	43
4303	Monte Patria	43
4304	Punitaqui	43
4305	Río Hurtado	43
5101	Valparaíso	51
5102	Casablanca	51
5103	Concón	51
5104	Juan Fernández	51
5105	Puchuncaví	51
5107	Quintero	51
5109	Viña Del Mar	51
5201	Isla De Pascua	52
5301	Los Andes	53
5302	Calle Larga	53
5303	Rinconada	53
5304	San Esteban	53
5401	La Ligua	54
5402	Cabildo	54
5403	Papudo	54
5404	Petorca	54
5405	Zapallar	54
5501	Quillota	55
5502	La Calera	55
5503	Hijuelas	55
5504	La Cruz	55
5506	Nogales	55
5601	San Antonio	56
5602	Algarrobo	56
5603	Cartagena	56
5604	El Quisco	56
5605	El Tabo	56
5606	Santo Domingo	56
5701	San Felipe	57
5702	Catemu	57
5703	Llay Llay	57
5704	Panquehue	57
5705	Putaendo	57
5706	Santa María	57
5801	Quilpué	58
5802	Limache	58
5803	Olmué	58
5804	Villa Alemana	58
6101	Rancagua	61
6102	Codegua	61
6103	Coinco	61
6104	Coltauco	61
6105	Doñihue	61
6106	Graneros	61
6107	Las Cabras	61
6108	Machalí	61
6109	Malloa	61
6110	Mostazal	61
6111	Olivar	61
6112	Peumo	61
6113	Pichidegua	61
6114	Quinta De Tilcoco	61
6115	Rengo	61
6116	Requínoa	61
6117	San Vicente	61
6201	Pichilemu	62
6202	La Estrella	62
6203	Litueche	62
6204	Marchihue	62
6205	Navidad	62
6206	Paredones	62
6301	San Fernando	63
6302	Chépica	63
6303	Chimbarongo	63
6304	Lolol	63
6305	Nancagua	63
6306	Palmilla	63
6307	Peralillo	63
6308	Placilla	63
6309	Pumanque	63
6310	Santa Cruz	63
7101	Talca	71
7102	Constitución	71
7103	Curepto	71
7104	Empedrado	71
7105	Maule	71
7106	Pelarco	71
7107	Pencahue	71
7108	Río Claro	71
7109	San Clemente	71
7110	San Rafael	71
7201	Cauquenes	72
7202	Chanco	72
7203	Pelluhue	72
7301	Curicó	73
7302	Hualañé	73
7303	Licantén	73
7304	Molina	73
7305	Rauco	73
7306	Romeral	73
7307	Sagrada Familia	73
7308	Teno	73
7309	Vichuquén	73
7401	Linares	74
7402	Colbún	74
7403	Longaví	74
7404	Parral	74
7405	Retiro	74
7406	San Javier	74
7407	Villa Alegre	74
7408	Yerbas Buenas	74
8101	Concepción	81
8102	Coronel	81
8103	Chiguayante	81
8104	Florida	81
8105	Hualqui	81
8106	Lota	81
8107	Penco	81
8108	San Pedro De La Paz	81
8109	Santa Juana	81
8110	Talcahuano	81
8111	Tomé	81
8112	Hualpén	81
8201	Lebu	82
8202	Arauco	82
8203	Cañete	82
8204	Contulmo	82
8205	Curanilahue	82
8206	Los Álamos	82
8207	Tirúa	82
8301	Los Ángeles	83
8302	Antuco	83
8303	Cabrero	83
8304	Laja	83
8305	Mulchén	83
8306	Nacimiento	83
8307	Negrete	83
8308	Quilaco	83
8309	Quilleco	83
8310	San Rosendo	83
8311	Santa Bárbara	83
8312	Tucapel	83
8313	Yumbel	83
8314	Alto Biobío	83
9101	Temuco	91
9102	Carahue	91
9103	Cunco	91
9104	Curarrehue	91
9105	Freire	91
9106	Galvarino	91
9107	Gorbea	91
9108	Lautaro	91
9109	Loncoche	91
9110	Melipeuco	91
9111	Nueva Imperial	91
9112	Padre Las Casas	91
9113	Perquenco	91
9114	Pitrufquén	91
9115	Pucón	91
9116	Saavedra	91
9117	Teodoro Schmidt	91
9118	Toltén	91
9119	Vilcún	91
9120	Villarrica	91
9121	Cholchol	91
9201	Angol	92
9202	Collipulli	92
9203	Curacautín	92
9204	Ercilla	92
9205	Lonquimay	92
9206	Los Sauces	92
9207	Lumaco	92
9208	Purén	92
9209	Renaico	92
9210	Traiguén	92
9211	Victoria	92
10101	Puerto Montt	101
10102	Calbuco	101
10103	Cochamó	101
10104	Fresia	101
10105	Frutillar	101
10106	Los Muermos	101
10107	Llanquihue	101
10108	Maullín	101
10109	Puerto Varas	101
10201	Castro	102
10202	Ancud	102
10203	Chonchi	102
10204	Curaco De Vélez	102
10205	Dalcahue	102
10206	Puqueldón	102
10207	Queilén	102
10208	Quellón	102
10209	Quemchi	102
10210	Quinchao	102
10301	Osorno	103
10302	Puerto Octay	103
10303	Purranque	103
10304	Puyehue	103
10305	Río Negro	103
10306	San Juan De La Costa	103
10307	San Pablo	103
10401	Chaitén	104
10402	Futaleufú	104
10403	Hualaihué	104
10404	Palena	104
11101	Coyhaique	111
11102	Lago Verde	111
11201	Aysén	112
11202	Cisnes	112
11203	Guaitecas	112
11301	Cochrane	113
11302	O'Higgins	113
11303	Tortel	113
11401	Chile Chico	114
11402	Río Ibáñez	114
12101	Punta Arenas	121
12102	Laguna Blanca	121
12103	Río Verde	121
12104	San Gregorio	121
12201	Cabo De Hornos	122
12202	Antártica	122
12301	Porvenir	123
12302	Primavera	123
12303	Timaukel	123
12401	Natales	124
12402	Torres Del Paine	124
13101	Santiago	131
13102	Cerrillos	131
13103	Cerro Navia	131
13104	Conchalí	131
13105	El Bosque	131
13106	Estación Central	131
13107	Huechuraba	131
13108	Independencia	131
13109	La Cisterna	131
13110	La Florida	131
13111	La Granja	131
13112	La Pintana	131
13113	La Reina	131
13114	Las Condes	131
13115	Lo Barnechea	131
13116	Lo Espejo	131
13117	Lo Prado	131
13118	Macul	131
13119	Maipú	131
13120	Ñuñoa	131
13121	Pedro Aguirre Cerda	131
13122	Peñalolén	131
13123	Providencia	131
13124	Pudahuel	131
13125	Quilicura	131
13126	Quinta Normal	131
13127	Recoleta	131
13128	Renca	131
13129	San Joaquín	131
13130	San Miguel	131
13131	San Ramón	131
13132	Vitacura	131
13201	Puente Alto	132
13202	Pirque	132
13203	San José De Maipo	132
13301	Colina	133
13302	Lampa	133
13303	Tiltil	133
13401	San Bernardo	134
13402	Buin	134
13403	Calera De Tango	134
13404	Paine	134
13501	Melipilla	135
13502	Alhué	135
13503	Curacaví	135
13504	María Pinto	135
13505	San Pedro	135
13601	Talagante	136
13602	El Monte	136
13603	Isla De Maipo	136
13604	Padre Hurtado	136
13605	Peñaflor	136
14101	Valdivia	141
14102	Corral	141
14103	Lanco	141
14104	Los Lagos	141
14105	Máfil	141
14106	Mariquina	141
14107	Paillaco	141
14108	Panguipulli	141
14201	La Unión	142
14202	Futrono	142
14203	Lago Ranco	142
14204	Río Bueno	142
15101	Arica	151
15102	Camarones	151
15201	Putre	152
15202	General Lagos	152
8401	Chillán	163
8402	Bulnes	163
8406	Chillán Viejo	163
8407	El Carmen	163
8410	Pemuco	163
8411	Pinto	163
8413	Quillón	163
8418	San Ignacio	163
8421	Yungay	163
8403	Cobquecura	162
8404	Coelemu	162
8408	Ninhue	162
8412	Portezuelo	162
8414	Quirihue	162
8415	Ránquil	162
8420	Treguaco	162
8405	Coihueco	161
8409	Ñiquén	161
8416	San Carlos	161
8417	San Fabián	161
8419	San Nicolás	161
\.


--
-- Data for Name: contador; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.contador (count) FROM stdin;
0
\.


--
-- Data for Name: detalle; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.detalle (cod_h, cantidad, total_detalle, id_a, empresa, estado, cod_sucursal, total_unitario, descuento) FROM stdin;
1676326	2	19980	28	96792430	PENDIENTE	3	\N	\N
1791710	1	8990	28	96792430	PENDIENTE	1	\N	\N
1676326	2	18600	29	96792430	PENDIENTE	1	\N	\N
2646528	2	21585	30	96792430	PENDIENTE	1	10792	25
2646528	3	32378	31	96792430	PENDIENTE	1	10792	25
1791710	1	8990	31	96792430	PENDIENTE	1	8990	0
1878141	1	5090	32	96792430	COMPLETADO	1	5090	0
1878141	1	5090	33	96792430	COMPLETADO	1	5090	0
2646528	5	57560	32	96792430	ANULADO	1	11512	20
\.


--
-- Data for Name: empresa; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.empresa (cod_empresa, nombre) FROM stdin;
96792430	SODIMAC S.A.
\.


--
-- Data for Name: herramienta; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.herramienta (cod_herramienta, nombre, descripcion, url_foto, cod_categoria, empresa) FROM stdin;
1676326	DEMOLEDOR ELÉCTRICO 17,0 KG, MAKITA	DEMOLICIÓN DE MUROS Y RADIERES.	demoledor2.jpg	4	96792430
513962	DEMOLEDOR ELÉCTRICO 32 KG, MAKITA	DEMOLICIÓN DE MUROS, CIMIENTOS O PAVIMENTOS.	demoledor3.jpg	4	96792430
2646528	ALISADOR DE HORMIGÓN 36 PULGADAS 9,0 HP, WACKER	PARA ALISADO DE SUPERFICIES DE HORMIGÓN FRESCO, HASTA 2 HORAS DE APLICADO.	alisador.jpg	3	96792430
2646595	CORTADORA DE PAVIMENTO 14 HP, WACKER	CORTE DE ASFALTOS Y PAVIMENTOS CON DISCO DE 14 Y 16 PULGADAS.	cortadora.jpg	3	96792430
3087158	TROMPO ELÉCTRICO 150 LITROS, TECNAMAQ	TROMPO DE VOLTEO DIRECTO PARA LA ELABORACIÓN DE MEZCLAS DE HORMIGÓN Y MORTEROS. CAPACIDAD MÁXIMA 150 LITROS. MOTOR 1,5 HP DE 220 VOLTS. APTO PARA TRABAJOS MAYORES DE CONSTRUCCIÓN.	trompo.jpg	3	96792430
2738831	TROMPO ELÉCTRICO 350 LITROS 2HP, EMARESA	TROMPO DE VOLTEO DIRECTO PARA LA ELABORACIÓN DE MEZCLAS DE HORMIGÓN Y MORTEROS. CAPACIDAD MÁXIMA 350 LITROS. MOTOR 2 HP DE 220 VOLTS. MECANISMO DE SEGURIDAD, SIN CREMALLERAS EXPUESTAS.	trompo2.jpg	3	96792430
2646684	PLACA COMPACTADORA 2000 KN, WACKER	CCOMPACTACIÓN DE TERRENOS GRANULARES DE MEDIANA DENSIDAD.	compactadora.jpg	2	96792430
2646692	PLACA COMPACTADORA 2500 KN, WACKER	CCOMPACTACIÓN DE TERRENOS GRANULARES DE ALTA DENSIDAD.	compactadora2.jpg	2	96792430
1953079	RODILLO COMPACTADOR 4000 KN, BOMAG	COMPACTACIÓN DE ASFALTOS Y TERRENOS GRANULARES EN EXTENSIÓN.	rodillo.jpg	2	96792430
2646781	VIBROPISÓN DIESEL 4,1 HP, WACKER	COMPACTACIÓN DE TERRENOS GRANULARES REDUCIDOS, ESPECIALMENTE ZANJAS.	vibropison.jpg	2	96792430
932965	LIJADORA ORBITAL 600 W, MAKITA	TRABAJOS DE LIJADO EN CARPINTERÍA Y MUEBLERÍA FINA.	lijadora.jpg	1	96792430
933600	CEPILLO ELÉCTRICO 155 MM, MAKITA	CEPILLADO Y ALISADO DE MADERAS (PALOS Y TABLAS) SOBRE 5 PULGADAS DE ANCHO.	cepillo.jpg	1	96792430
933181	LIJADORA DE PULIDO 1250 W, MAKITA	TRABAJOS DE PULIDO EN CARPINTERÍA, CONSTRUCCIÓN Y VEHÍCULOS.	pulido.jpg	1	96792430
1594265	SIERRA CIRCULAR 185 MM, MAKITA	CORTE DE MADERAS (PALOS Y TABLEROS) HASTA 3 PULGADAS.	circular.jpg	1	96792430
1791680	ASPIRADORA INDUSTRIAL 2 MOTORES 2000 W, LUSTER	PARA ASPIRADO DE POLVO Y AGUA Y RESIDUOS LIGEROS.	aspiradora.jpg	5	96792430
1791699	ASPIRADORA SEMI-INDUSTRIAL A MOTOR 1000 W, LUSTER	PARA ASPIRADO DE POLVO Y AGUA Y RESIDUOS LIGEROS.	aspiradora2.jpg	5	96792430
2619407	HIDROLAVADORA ELÉCTRICA DE AGUA FRÍA 3,9 HP, KARCHER	LAVADO A PRESIÓN DE VEHÍCULOS Y SUPERFICIES.	hidrolavadora.jpg	5	96792430
1791710	LAVADORA DE ALFOMBRAS Y PISOS DUROS 1200 W, LUSTER, KARCHER	LAVADORA INDUSTRIAL DE ALFOMBRAS DE PISOS DUROS (CERÁMICOS, BALDOSAS, ETC.).	lavadora.jpg	5	96792430
930385	CINCELADOR ELÉCTRICO 8,7 KG, MAKITA	DEMOLICIÓN O CONFECCION DE SURCOS EN CONCRETOS MENORES.	cincel.jpg	4	96792430
1613774	DEMOLEDOR ELÉCTRICO 9,2 KG, MAKITA	DEMOLICIÓN DE MUROS Y RADIERES.	demoledor.jpg	4	96792430
2839482	ROTOMARTILLO SDS-PLUS 2,9 KG, MAKITA	PERFORACIÓN EN HORMIGÓN, PIEDRA Y CONCRETOS DUROS. USO CON BROCAS TIPO SDS-PLUS HASTA 20 MM	rotomartillo.jpg	4	96792430
3097166	TROMPO ELÉCTRICO 130 LITROS, TECNAMAQ 	TROMPO DE VOLTEO DIRECTO PARA LA ELABORACIÓN DE MEZCLAS DE HORMIGÓN Y MORTEROS. CAPACIDAD MÁXIMA 130 LITROS. MOTOR 1,5 HP DE 220 VOLTS. APTO PARA TRABAJOS MENORES EN EL HOGAR	trompo1.jpg	3	96792430
1878141	FRESADORA INDUSTRIAL 8 MM, MAKITA	FRESADO Y MOLDEADO EN BORDES DE MADERAS Y SUPERFICIES DE TABLEROS.	fresadora.jpg	1	96792430
\.


--
-- Data for Name: provincia; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.provincia (provincia_id, provincia_nombre, provincia_region_id) FROM stdin;
11	Iquique	1
14	Tamarugal	1
21	Antofagasta	2
22	El Loa	2
23	Tocopilla	2
31	Copiapó	3
32	Chañaral	3
33	Huasco	3
41	Elqui	4
42	Choapa	4
43	Limarí	4
51	Valparaíso	5
52	Isla De Pascua	5
53	Los Andes	5
54	Petorca	5
55	Quillota	5
56	San Antonio	5
57	San Felipe De Aconcagua	5
58	Marga Marga	5
61	Cachapoal	6
62	Cardenal Caro	6
63	Colchagua	6
71	Talca	7
72	Cauquenes	7
73	Curicó	7
74	Linares	7
81	Concepción	8
82	Arauco	8
83	Biobío	8
91	Cautín	9
92	Malleco	9
101	Llanquihue	10
102	Chiloé	10
103	Osorno	10
104	Palena	10
111	Coihaique	11
112	Aisén	11
113	Capitán Prat	11
114	General Carrera	11
121	Magallanes	12
122	Antártica Chilena	12
123	Tierra Del Fuego	12
124	Última Esperanza	12
131	Santiago	13
132	Cordillera	13
133	Chacabuco	13
134	Maipo	13
135	Melipilla	13
136	Talagante	13
141	Valdivia	14
142	Ranco	14
151	Arica	15
152	Parinacota	15
163	Diguillín	16
162	Itata	16
161	Punilla	16
\.


--
-- Data for Name: region; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.region (region_id, region_nombre) FROM stdin;
1	Tarapacá
2	Antofagasta
3	Atacama
4	Coquimbo
5	Valparaíso
6	Región Del Libertador Gral. Bernardo O'Higgins
7	Región Del Maule
8	Región Del Biobío
9	Región De La Araucanía
10	Región De Los Lagos
11	Región Aisén Del Gral. Carlos Ibáñez Del Campo
12	Región De Magallanes Y De La Antártica Chilena
13	Región Metropolitana De Santiago
14	Región De Los Ríos
15	Arica Y Parinacota
16	Región De Ñuble
\.


--
-- Data for Name: sucursal; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.sucursal (cod_sucursal, nombre, direccion, telefono, url_foto, cod_empresa, comuna) FROM stdin;
1	SODIMAC TALCA	AV SAN MIGUEL N°12	987654321		96792430	7101
3	SODIMAC TALCA COLIN	Av. Colín 635	968673726	96792430	96792430	7101
2	SODIMAC LINARES	AV. Aníbal León Bustos 0376	123456788	96792430	96792430	7401
5	SODIMAC HOMECENTER SANTA CRUZ	Rafael Casanova 412, Santa Cruz	722410200	\N	96792430	6310
\.


--
-- Data for Name: sucursal_herramienta; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.sucursal_herramienta (cod_herramienta, cod_sucursal, stock, precio, empresa, descuento, f_inicio_d, f_final_d) FROM stdin;
930385	2	2	7990	96792430	\N	\N	\N
1613774	1	7	6490	96792430	\N	\N	\N
1613774	2	12	5900	96792430	\N	\N	\N
1676326	2	1	9990	96792430	\N	\N	\N
513962	2	5	10500	96792430	\N	\N	\N
2646528	2	3	15490	96792430	\N	\N	\N
2646595	1	3	13290	96792430	\N	\N	\N
2646595	2	1	13990	96792430	\N	\N	\N
3087158	2	5	6590	96792430	\N	\N	\N
2646684	1	4	7090	96792430	\N	\N	\N
2646684	2	1	7290	96792430	\N	\N	\N
2646692	2	2	11790	96792430	\N	\N	\N
1953079	1	2	22890	96792430	\N	\N	\N
1953079	2	1	21890	96792430	\N	\N	\N
2646781	1	5	11590	96792430	\N	\N	\N
932965	1	6	4590	96792430	\N	\N	\N
932965	2	3	4590	96792430	\N	\N	\N
933600	1	2	5390	96792430	\N	\N	\N
933600	2	3	5390	96792430	\N	\N	\N
1878141	2	1	5390	96792430	\N	\N	\N
933181	1	6	4290	96792430	\N	\N	\N
933181	2	5	4390	96792430	\N	\N	\N
1594265	2	2	4090	96792430	\N	\N	\N
1594265	1	3	4290	96792430	\N	\N	\N
1791680	1	3	8090	96792430	\N	\N	\N
1791699	1	2	5590	96792430	\N	\N	\N
2619407	1	5	8190	96792430	\N	\N	\N
1791710	1	1	8990	96792430	\N	\N	\N
1676326	3	6	9990	96792430	\N	\N	\N
1676326	5	6	9990	96792430	\N	\N	\N
3087158	1	5	6590	96792430	15	2018-12-03	2018-12-07
1676326	1	4	9300	96792430	10	2018-12-03	2018-12-07
1878141	1	1	5090	96792430	25	2018-12-02	2018-12-03
2738831	1	2	17290	96792430	30	2018-12-03	2018-12-05
2646528	1	12	14390	96792430	10	2018-12-02	2018-12-05
930385	1	5	7690	96792430	5	2018-12-02	2018-12-09
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: jefe
--

COPY public.usuario (rut, nombres, apellidos, correo, rol, pass, estado, direccion, celular) FROM stdin;
97784507	FELIPE	TAPIA	FTAPIA46@GMAIL.COM	CLIENTE	COr8Z9S1OHXNw	1	DIRECCION	986520661
86931133	JUAN	TAPIA	TEST@UCM.CL	CLIENTE	COgJKBV0j39tQ	1	ASDASDAS	987654321
123123123	FGYY	GGGG	UJJIK@BB.CL	CLIENTE	COqYP9OnpFQ/o	1	MGFYG	565655
183441396	CLAUDIA ALEJANDRA	ROJAS SALDAÑA	CROJAS@UCM.CL	CLIENTE	COgJKBV0j39tQ	1	SU CARA	987654321
185756203	FELIPE MAXIMILIANO	TAPIA GOMEZ	FTAPIA46@GMAIL.COM	CLIENTE	COctHf0I1iMPk	1	DIRECCION	986520661
\.


--
-- Name: arriendo_ai; Type: SEQUENCE SET; Schema: public; Owner: jefe
--

SELECT pg_catalog.setval('public.arriendo_ai', 33, true);


--
-- Name: categoria_ai; Type: SEQUENCE SET; Schema: public; Owner: jefe
--

SELECT pg_catalog.setval('public.categoria_ai', 1, false);


--
-- Name: empresa_ai; Type: SEQUENCE SET; Schema: public; Owner: jefe
--

SELECT pg_catalog.setval('public.empresa_ai', 1, false);


--
-- Name: sucursal_ai; Type: SEQUENCE SET; Schema: public; Owner: jefe
--

SELECT pg_catalog.setval('public.sucursal_ai', 6, true);


--
-- Name: administrador pk_administrador; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.administrador
    ADD CONSTRAINT pk_administrador PRIMARY KEY (rut);


--
-- Name: arriendo pk_arriendo; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.arriendo
    ADD CONSTRAINT pk_arriendo PRIMARY KEY (cod_arriendo);


--
-- Name: carrito pk_carrito; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT pk_carrito PRIMARY KEY (cod_herramienta, rut, empresa, cod_sucursal);


--
-- Name: categoria pk_categoria; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT pk_categoria PRIMARY KEY (cod_categoria);


--
-- Name: comuna pk_comuna; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.comuna
    ADD CONSTRAINT pk_comuna PRIMARY KEY (comuna_id);


--
-- Name: detalle pk_detalle; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.detalle
    ADD CONSTRAINT pk_detalle PRIMARY KEY (cod_h, id_a, empresa, cod_sucursal);


--
-- Name: empresa pk_empresa; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.empresa
    ADD CONSTRAINT pk_empresa PRIMARY KEY (cod_empresa);


--
-- Name: herramienta pk_herramienta; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.herramienta
    ADD CONSTRAINT pk_herramienta PRIMARY KEY (cod_herramienta, empresa);


--
-- Name: provincia pk_provincia; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.provincia
    ADD CONSTRAINT pk_provincia PRIMARY KEY (provincia_id);


--
-- Name: region pk_region; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.region
    ADD CONSTRAINT pk_region PRIMARY KEY (region_id);


--
-- Name: sucursal pk_sucursal; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.sucursal
    ADD CONSTRAINT pk_sucursal PRIMARY KEY (cod_sucursal);


--
-- Name: sucursal_herramienta pk_sucursal_herramienta; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.sucursal_herramienta
    ADD CONSTRAINT pk_sucursal_herramienta PRIMARY KEY (cod_herramienta, cod_sucursal, empresa);


--
-- Name: usuario pk_usuario; Type: CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (rut);


--
-- Name: detalle actualiza_total_arriendo; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER actualiza_total_arriendo AFTER INSERT ON public.detalle FOR EACH ROW EXECUTE PROCEDURE public.actualiza_total_arriendo();


--
-- Name: carrito ajusta_total_carrito_insert; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER ajusta_total_carrito_insert BEFORE INSERT ON public.carrito FOR EACH ROW EXECUTE PROCEDURE public.ajusta_total_carrito_insert();


--
-- Name: carrito ajusta_total_carrito_update; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER ajusta_total_carrito_update BEFORE UPDATE ON public.carrito FOR EACH ROW EXECUTE PROCEDURE public.ajusta_total_carrito_update();


--
-- Name: sucursal_herramienta descuento_total; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER descuento_total AFTER INSERT OR UPDATE ON public.sucursal_herramienta FOR EACH ROW EXECUTE PROCEDURE public.descuento_total();


--
-- Name: comuna mayus_comuna; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER mayus_comuna BEFORE INSERT ON public.comuna FOR EACH ROW EXECUTE PROCEDURE public.mayus_comuna();


--
-- Name: herramienta mayus_herramienta; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER mayus_herramienta BEFORE INSERT ON public.herramienta FOR EACH ROW EXECUTE PROCEDURE public.mayus_herramienta();


--
-- Name: provincia mayus_provincia; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER mayus_provincia BEFORE INSERT ON public.provincia FOR EACH ROW EXECUTE PROCEDURE public.mayus_provincia();


--
-- Name: region mayus_region; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER mayus_region BEFORE INSERT ON public.region FOR EACH ROW EXECUTE PROCEDURE public.mayus_region();


--
-- Name: sucursal mayus_sucursal; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER mayus_sucursal BEFORE INSERT ON public.sucursal FOR EACH ROW EXECUTE PROCEDURE public.mayus_sucursal();


--
-- Name: usuario mayus_usuario; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER mayus_usuario BEFORE INSERT OR UPDATE ON public.usuario FOR EACH ROW EXECUTE PROCEDURE public.mayus_usuario();


--
-- Name: detalle modificar_detalle_t; Type: TRIGGER; Schema: public; Owner: jefe
--

CREATE TRIGGER modificar_detalle_t AFTER UPDATE ON public.detalle FOR EACH ROW EXECUTE PROCEDURE public.modificar_detalle_t();


--
-- Name: administrador fk_administrador1; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.administrador
    ADD CONSTRAINT fk_administrador1 FOREIGN KEY (empresa) REFERENCES public.empresa(cod_empresa) ON DELETE CASCADE;


--
-- Name: administrador fk_administrador2; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.administrador
    ADD CONSTRAINT fk_administrador2 FOREIGN KEY (comuna) REFERENCES public.comuna(comuna_id) ON DELETE CASCADE;


--
-- Name: administrador fk_administrador3; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.administrador
    ADD CONSTRAINT fk_administrador3 FOREIGN KEY (rut_a) REFERENCES public.administrador(rut);


--
-- Name: detalle fk_arriendo; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.detalle
    ADD CONSTRAINT fk_arriendo FOREIGN KEY (id_a) REFERENCES public.arriendo(cod_arriendo) ON DELETE CASCADE;


--
-- Name: carrito fk_carrito; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT fk_carrito FOREIGN KEY (rut) REFERENCES public.usuario(rut) ON DELETE CASCADE;


--
-- Name: carrito fk_carrito1; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT fk_carrito1 FOREIGN KEY (cod_herramienta, empresa) REFERENCES public.herramienta(cod_herramienta, empresa) ON DELETE CASCADE;


--
-- Name: carrito fk_carrito2; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT fk_carrito2 FOREIGN KEY (cod_sucursal) REFERENCES public.sucursal(cod_sucursal) ON DELETE CASCADE;


--
-- Name: herramienta fk_categoria; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.herramienta
    ADD CONSTRAINT fk_categoria FOREIGN KEY (cod_categoria) REFERENCES public.categoria(cod_categoria) ON DELETE CASCADE;


--
-- Name: comuna fk_comuna; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.comuna
    ADD CONSTRAINT fk_comuna FOREIGN KEY (comuna_provincia_id) REFERENCES public.provincia(provincia_id) ON DELETE CASCADE;


--
-- Name: herramienta fk_empresa; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.herramienta
    ADD CONSTRAINT fk_empresa FOREIGN KEY (empresa) REFERENCES public.empresa(cod_empresa) ON DELETE CASCADE;


--
-- Name: provincia fk_provincia; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.provincia
    ADD CONSTRAINT fk_provincia FOREIGN KEY (provincia_region_id) REFERENCES public.region(region_id) ON DELETE CASCADE;


--
-- Name: sucursal fk_sucursal; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.sucursal
    ADD CONSTRAINT fk_sucursal FOREIGN KEY (cod_empresa) REFERENCES public.empresa(cod_empresa) ON DELETE CASCADE;


--
-- Name: detalle fk_sucursal; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.detalle
    ADD CONSTRAINT fk_sucursal FOREIGN KEY (cod_sucursal) REFERENCES public.sucursal(cod_sucursal) ON DELETE CASCADE;


--
-- Name: sucursal fk_sucursal2; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.sucursal
    ADD CONSTRAINT fk_sucursal2 FOREIGN KEY (comuna) REFERENCES public.comuna(comuna_id) ON DELETE CASCADE;


--
-- Name: sucursal_herramienta fk_sucursal_herramienta; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.sucursal_herramienta
    ADD CONSTRAINT fk_sucursal_herramienta FOREIGN KEY (cod_herramienta, empresa) REFERENCES public.herramienta(cod_herramienta, empresa) ON DELETE CASCADE;


--
-- Name: detalle fk_sucursal_herramienta; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.detalle
    ADD CONSTRAINT fk_sucursal_herramienta FOREIGN KEY (cod_h, empresa, cod_sucursal) REFERENCES public.sucursal_herramienta(cod_herramienta, empresa, cod_sucursal) ON DELETE CASCADE;


--
-- Name: sucursal_herramienta fk_sucursal_herramienta2; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.sucursal_herramienta
    ADD CONSTRAINT fk_sucursal_herramienta2 FOREIGN KEY (cod_sucursal) REFERENCES public.sucursal(cod_sucursal) ON DELETE CASCADE;


--
-- Name: arriendo fk_usuario; Type: FK CONSTRAINT; Schema: public; Owner: jefe
--

ALTER TABLE ONLY public.arriendo
    ADD CONSTRAINT fk_usuario FOREIGN KEY (rut_u) REFERENCES public.usuario(rut) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO jefe;
GRANT USAGE ON SCHEMA public TO pipemax;
GRANT USAGE ON SCHEMA public TO admin;
GRANT USAGE ON SCHEMA public TO admins;


--
-- Name: FUNCTION armor(bytea); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.armor(bytea) TO pipemax;


--
-- Name: FUNCTION armor(bytea, text[], text[]); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.armor(bytea, text[], text[]) TO pipemax;


--
-- Name: FUNCTION check_fecha_final(finicio date, ffinal date); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.check_fecha_final(finicio date, ffinal date) TO pipemax;


--
-- Name: FUNCTION checkadmin(rut_u integer); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.checkadmin(rut_u integer) TO admins;


--
-- Name: FUNCTION checkcategoria(codigo integer); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.checkcategoria(codigo integer) TO pipemax;


--
-- Name: FUNCTION crypt(text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.crypt(text, text) TO pipemax;


--
-- Name: FUNCTION dearmor(text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.dearmor(text) TO pipemax;


--
-- Name: FUNCTION decrypt(bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.decrypt(bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION decrypt_iv(bytea, bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.decrypt_iv(bytea, bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION digest(bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.digest(bytea, text) TO pipemax;


--
-- Name: FUNCTION digest(text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.digest(text, text) TO pipemax;


--
-- Name: FUNCTION encrypt(bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.encrypt(bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION encrypt_iv(bytea, bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.encrypt_iv(bytea, bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION gen_random_bytes(integer); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.gen_random_bytes(integer) TO pipemax;


--
-- Name: FUNCTION gen_random_uuid(); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.gen_random_uuid() TO pipemax;


--
-- Name: FUNCTION gen_salt(text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.gen_salt(text) TO pipemax;


--
-- Name: FUNCTION gen_salt(text, integer); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.gen_salt(text, integer) TO pipemax;


--
-- Name: FUNCTION hmac(bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.hmac(bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION hmac(text, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.hmac(text, text, text) TO pipemax;


--
-- Name: FUNCTION inicio_sesion_admin(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.inicio_sesion_admin(rut_u integer, pass_u character varying, OUT bool character varying, OUT message character varying) TO admins;


--
-- Name: FUNCTION insertar_usuario(rut_u integer, nombres_u character varying, apellidos_u character varying, correo_u character varying, pass_u character varying, direccion_u character varying, celular_u integer, OUT bool character varying, OUT message character varying); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.insertar_usuario(rut_u integer, nombres_u character varying, apellidos_u character varying, correo_u character varying, pass_u character varying, direccion_u character varying, celular_u integer, OUT bool character varying, OUT message character varying) TO pipemax;


--
-- Name: FUNCTION pgp_armor_headers(text, OUT key text, OUT value text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_armor_headers(text, OUT key text, OUT value text) TO pipemax;


--
-- Name: FUNCTION pgp_key_id(bytea); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_key_id(bytea) TO pipemax;


--
-- Name: FUNCTION pgp_pub_decrypt(bytea, bytea); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_decrypt(bytea, bytea) TO pipemax;


--
-- Name: FUNCTION pgp_pub_decrypt(bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_decrypt(bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_pub_decrypt(bytea, bytea, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_decrypt(bytea, bytea, text, text) TO pipemax;


--
-- Name: FUNCTION pgp_pub_decrypt_bytea(bytea, bytea); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_decrypt_bytea(bytea, bytea) TO pipemax;


--
-- Name: FUNCTION pgp_pub_decrypt_bytea(bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_decrypt_bytea(bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_pub_decrypt_bytea(bytea, bytea, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_decrypt_bytea(bytea, bytea, text, text) TO pipemax;


--
-- Name: FUNCTION pgp_pub_encrypt(text, bytea); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_encrypt(text, bytea) TO pipemax;


--
-- Name: FUNCTION pgp_pub_encrypt(text, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_encrypt(text, bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_pub_encrypt_bytea(bytea, bytea); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_encrypt_bytea(bytea, bytea) TO pipemax;


--
-- Name: FUNCTION pgp_pub_encrypt_bytea(bytea, bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_pub_encrypt_bytea(bytea, bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_decrypt(bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_decrypt(bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_decrypt(bytea, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_decrypt(bytea, text, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_decrypt_bytea(bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_decrypt_bytea(bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_decrypt_bytea(bytea, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_decrypt_bytea(bytea, text, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_encrypt(text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_encrypt(text, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_encrypt(text, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_encrypt(text, text, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_encrypt_bytea(bytea, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_encrypt_bytea(bytea, text) TO pipemax;


--
-- Name: FUNCTION pgp_sym_encrypt_bytea(bytea, text, text); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.pgp_sym_encrypt_bytea(bytea, text, text) TO pipemax;


--
-- Name: FUNCTION vaciar_carro(rut_u integer); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.vaciar_carro(rut_u integer) TO pipemax;


--
-- Name: FUNCTION validacion(numerico integer); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.validacion(numerico integer) TO admins;


--
-- Name: FUNCTION validacion(numerico character varying); Type: ACL; Schema: public; Owner: jefe
--

REVOKE ALL ON FUNCTION public.validacion(numerico character varying) FROM PUBLIC;
GRANT ALL ON FUNCTION public.validacion(numerico character varying) TO pipemax;


--
-- Name: FUNCTION validar_login_admin(rut_c integer, pass_c character varying); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.validar_login_admin(rut_c integer, pass_c character varying) TO admins;


--
-- Name: FUNCTION verificar_descuentos(); Type: ACL; Schema: public; Owner: jefe
--

GRANT ALL ON FUNCTION public.verificar_descuentos() TO pipemax;


--
-- Name: TABLE administrador; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.administrador TO admins;


--
-- Name: TABLE arriendo; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,INSERT,UPDATE ON TABLE public.arriendo TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.arriendo TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.arriendo TO admins;


--
-- Name: SEQUENCE arriendo_ai; Type: ACL; Schema: public; Owner: jefe
--

GRANT USAGE ON SEQUENCE public.arriendo_ai TO pipemax;


--
-- Name: TABLE carrito; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.carrito TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.carrito TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.carrito TO admins;


--
-- Name: TABLE categoria; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT ON TABLE public.categoria TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.categoria TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.categoria TO admins;


--
-- Name: TABLE comuna; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT ON TABLE public.comuna TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.comuna TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.comuna TO admins;


--
-- Name: TABLE detalle; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,INSERT ON TABLE public.detalle TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.detalle TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.detalle TO admins;


--
-- Name: TABLE empresa; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.empresa TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.empresa TO admins;
GRANT SELECT ON TABLE public.empresa TO pipemax;


--
-- Name: TABLE herramienta; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT ON TABLE public.herramienta TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.herramienta TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.herramienta TO admins;


--
-- Name: TABLE provincia; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT ON TABLE public.provincia TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.provincia TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.provincia TO admins;


--
-- Name: TABLE region; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT ON TABLE public.region TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.region TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.region TO admins;


--
-- Name: TABLE sucursal; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT ON TABLE public.sucursal TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.sucursal TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.sucursal TO admins;


--
-- Name: SEQUENCE sucursal_ai; Type: ACL; Schema: public; Owner: jefe
--

GRANT USAGE ON SEQUENCE public.sucursal_ai TO admins;


--
-- Name: TABLE sucursal_herramienta; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,UPDATE ON TABLE public.sucursal_herramienta TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.sucursal_herramienta TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.sucursal_herramienta TO admins;


--
-- Name: COLUMN sucursal_herramienta.descuento; Type: ACL; Schema: public; Owner: jefe
--

GRANT UPDATE(descuento) ON TABLE public.sucursal_herramienta TO pipemax;


--
-- Name: COLUMN sucursal_herramienta.f_inicio_d; Type: ACL; Schema: public; Owner: jefe
--

GRANT UPDATE(f_inicio_d) ON TABLE public.sucursal_herramienta TO pipemax;


--
-- Name: COLUMN sucursal_herramienta.f_final_d; Type: ACL; Schema: public; Owner: jefe
--

GRANT UPDATE(f_final_d) ON TABLE public.sucursal_herramienta TO pipemax;


--
-- Name: TABLE usuario; Type: ACL; Schema: public; Owner: jefe
--

GRANT SELECT,INSERT,UPDATE ON TABLE public.usuario TO pipemax;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.usuario TO admin;
GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE public.usuario TO admins;


--
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON SEQUENCES  TO jefe WITH GRANT OPTION;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT USAGE ON SEQUENCES  TO pipemax;


--
-- Name: DEFAULT PRIVILEGES FOR TYPES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TYPES  TO jefe;


--
-- Name: DEFAULT PRIVILEGES FOR FUNCTIONS; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON FUNCTIONS  TO jefe WITH GRANT OPTION;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON FUNCTIONS  TO pipemax;


--
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TABLES  TO jefe WITH GRANT OPTION;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT SELECT ON TABLES  TO pipemax;


--
-- PostgreSQL database dump complete
--

