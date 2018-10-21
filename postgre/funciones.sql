CREATE OR REPLACE FUNCTION VERIFICA_HERRAMIENTA_SUCURSAL(
    CODIGO IN SUCURSAL_HERRAMIENTA.COD_HERRAMIENTA%TYPE,
    CODIGO_S IN SUCURSAL_HERRAMIENTA.COD_SUCURSAL%TYPE)
RETURNS BOOLEAN AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM SUCURSAL_HERRAMIENTA WHERE COD_HERRAMIENTA = CODIGO AND COD_SUCURSAL = CODIGO_S;
    IF (CONTADOR>0) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECKEMPRESA(
    CODIGO IN INT)
    RETURNS BOOLEAN AS $$
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECKSUCURSAL(
    CODIGO IN INT)
    RETURNS BOOLEAN AS $$
DECLARE
    CONTADOR INT;
BEGIN 
    SELECT COUNT(*) INTO CONTADOR FROM SUCURSAL WHERE COD_SUCURSAL=CODIGO;
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECK_FECHA_FINAL(
    FINICIO IN DATE,
    FFINAL IN DATE)
    RETURNS BOOLEAN AS $$
DECLARE
BEGIN
    IF FFINAL>FINICIO THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECK_CANTIDAD_CARRITO(
    CODIGO IN HERRAMIENTA.COD_HERRAMIENTA%TYPE,
    CODIGO_S IN SUCURSAL_HERRAMIENTA.COD_SUCURSAL%TYPE,
    RUT_U IN CARRITO.RUT%TYPE)
    RETURNS INT AS $$
DECLARE
    CANTIDAD_CARRO INT;
BEGIN
    SELECT CANTIDAD INTO CANTIDAD_CARRO FROM CARRITO 
    WHERE COD_HERRAMIENTA=CODIGO
    AND COD_SUCURSAL = CODIGO_S
    AND RUT = RUT_U;
    RETURN CANTIDAD_CARRO;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN -1;
END;
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECKCARRITO(
    CODIGO IN HERRAMIENTA.COD_HERRAMIENTA%TYPE,
    CODIGO_S IN SUCURSAL_HERRAMIENTA.COD_SUCURSAL%TYPE,
    RUT_U IN CARRITO.RUT%TYPE)
    RETURNS BOOLEAN AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM CARRITO 
    WHERE COD_HERRAMIENTA=CODIGO
    AND COD_SUCURSAL = CODIGO_S
    AND RUT = RUT_U;
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECKSTOCK(
    CODIGO IN SUCURSAL_HERRAMIENTA.COD_HERRAMIENTA%TYPE,
    CODIGO_S IN SUCURSAL_HERRAMIENTA.COD_SUCURSAL%TYPE,
    FECHA_I IN VARCHAR,
    FECHA_F IN VARCHAR)
    RETURNS INT AS $$
DECLARE
    CANTIDAD INT;
BEGIN    
    SELECT TEMPORAL.STOCK INTO CANTIDAD FROM
    (SELECT (SH.STOCK - SUM(D.CANTIDAD)) AS STOCK
    FROM HERRAMIENTA H JOIN CATEGORIA C 
    ON H.COD_CATEGORIA=C.COD_CATEGORIA
    JOIN SUCURSAL_HERRAMIENTA SH
    ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
    JOIN DETALLE D 
    ON D.COD_H=H.COD_HERRAMIENTA
    WHERE D.id_a IN (SELECT A.cod_arriendo FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                    ON D.COD_H=H.COD_HERRAMIENTA
                                    WHERE A.FECHA_INICIO BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                    OR A.FECHA_FINAL BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                    AND H.COD_HERRAMIENTA = CODIGO)
    AND SH.COD_SUCURSAL = CODIGO_S
    AND SH.COD_HERRAMIENTA = CODIGO
    GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, SH.STOCK   
    UNION 
    SELECT SH.STOCK AS STOCK
    FROM HERRAMIENTA H JOIN CATEGORIA C 
    ON H.COD_CATEGORIA=C.COD_CATEGORIA
    JOIN SUCURSAL_HERRAMIENTA SH
    ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
    FULL OUTER JOIN DETALLE D 
    ON D.COD_H=H.COD_HERRAMIENTA
    WHERE H.COD_HERRAMIENTA NOT IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                ON D.COD_H=H.COD_HERRAMIENTA
                                WHERE A.FECHA_INICIO BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                OR A.FECHA_FINAL BETWEEN TO_DATE(FECHA_I,'DD/MM/YYYY') AND TO_DATE(FECHA_F,'DD/MM/YYYY')
                                AND H.COD_HERRAMIENTA = CODIGO)
    AND SH.COD_SUCURSAL = CODIGO_S
    AND SH.COD_HERRAMIENTA = CODIGO) AS TEMPORAL;
    RETURN CANTIDAD;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN            
            SELECT STOCK INTO CANTIDAD FROM SUCURSAL_HERRAMIENTA WHERE COD_HERRAMIENTA=CODIGO AND COD_SUCURSAL = CODIGO_S;
            RETURN CANTIDAD;
END;
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION CHECKCATEGORIA(
    CODIGO IN CATEGORIA.COD_CATEGORIA%TYPE)
    RETURNS BOOLEAN AS $$
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/
SELECT * FROM GETPASS(123123123);
CREATE OR REPLACE FUNCTION GETPASS(
    RUT_U IN USUARIO.RUT%TYPE)
    RETURNS VARCHAR AS $$
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/
SELECT * FROM CHECKHERRAMIENTA(185756203);
CREATE OR REPLACE FUNCTION CHECKHERRAMIENTA(
    CODIGO IN INT)
    RETURNS BOOLEAN AS $$
DECLARE
    CONTADOR INT;
BEGIN
    SELECT COUNT(*) INTO CONTADOR FROM HERRAMIENTA WHERE COD_HERRAMIENTA=CODIGO;
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/
SELECT * FROM CHECKUSER(185756203);
CREATE OR REPLACE FUNCTION CHECKUSER(
    RUT_U IN INT)
    RETURNS BOOLEAN AS $$
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/
SELECT * FROM VALIDACION(86931133);

CREATE OR REPLACE FUNCTION VALIDACION(
    NUMERICO IN USUARIO.RUT%TYPE)
    RETURNS BOOLEAN AS $$
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
$$ LANGUAGE PLPGSQL;


/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION VERIFICAR_CARRO()
    RETURNS

/****************************************************************************************************************************************************************/
SELECT COD_HERRAMIENTA,CANTIDAD,COD_SUCURSAL,TOTAL,verificar_producto_venta('02/10/2018','10/10/2018',2,COD_HERRAMIENTA) as DISPONIBILIDAD FROM CARRITO WHERE RUT = 185756203 AND COD_SUCURSAL = 2 AND ESTADO = 1;
select * from verificar_producto_venta('09/10/2018','10/10/2018',2,1594265);
SELECT C.cod_herramienta, H.nombre, H.url_foto, C.cantidad, SH.stock, C.total, 
            verificar_producto_venta('03/10/2018','10/10/2018',C.cod_sucursal,C.cod_herramienta) as DISPONIBILIDAD,
            SH.precio FROM carrito C
            JOIN herramienta H
            ON C.cod_herramienta = H.cod_herramienta
            JOIN sucursal_herramienta SH
            ON SH.cod_herramienta = H.cod_herramienta
            WHERE SH.cod_sucursal = 2
            AND C.rut = 185756203;

CREATE OR REPLACE FUNCTION VERIFICAR_PRODUCTO_VENTA(
    FECHA_I IN ARRIENDO.FECHA_INICIO%TYPE,
    FECHA_F IN ARRIENDO.FECHA_FINAL%TYPE,
    COD_S IN SUCURSAL.COD_SUCURSAL%TYPE,
    COD_HE IN SUCURSAL_HERRAMIENTA.COD_HERRAMIENTA%TYPE)
    RETURNS INT AS $$
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
        FULL OUTER JOIN DETALLE D 
        ON D.COD_H=H.COD_HERRAMIENTA
        WHERE D.id_a IN (SELECT A.cod_arriendo FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                    ON D.COD_H=H.COD_HERRAMIENTA
                                    WHERE A.FECHA_INICIO BETWEEN FECHA_I AND FECHA_F
                                    OR A.FECHA_FINAL BETWEEN FECHA_I AND FECHA_F)
        AND SH.COD_SUCURSAL = COD_S
        AND SH.COD_HERRAMIENTA = COD_HE
        GROUP BY H.COD_HERRAMIENTA, H.NOMBRE, H.DESCRIPCION, H.URL_FOTO, SH.PRECIO, C.NOMBRE, SH.STOCK
        UNION 
        SELECT SH.STOCK
        FROM HERRAMIENTA H JOIN CATEGORIA C 
        ON H.COD_CATEGORIA=C.COD_CATEGORIA
        JOIN SUCURSAL_HERRAMIENTA SH
        ON H.COD_HERRAMIENTA = SH.COD_HERRAMIENTA
        FULL OUTER JOIN DETALLE D 
        ON D.COD_H=H.COD_HERRAMIENTA
        WHERE H.COD_HERRAMIENTA NOT IN (SELECT H.COD_HERRAMIENTA FROM ARRIENDO A JOIN DETALLE D 
                                    ON A.COD_ARRIENDO=D.ID_A JOIN HERRAMIENTA H
                                    ON D.COD_H=H.COD_HERRAMIENTA
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

CREATE OR REPLACE FUNCTION VACIAR_CARRO(
    RUT_U IN USUARIO.RUT%TYPE)
    RETURNS BOOLEAN AS $$
DECLARE
BEGIN
    LOCK TABLE CARRITO IN ROW EXCLUSIVE MODE;
    DELETE FROM CARRITO WHERE RUT = RUT_U;
    RETURN TRUE;
    EXCEPTION
        WHEN OTHERS THEN
            RETURN FALSE;
END;
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

SELECT * FROM VALIDAR_LOGIN(123123123,'AAAAAAAA');
DROP FUNCTION VALIDAR_LOGIN(INT,VARCHAR);
CREATE OR REPLACE FUNCTION VALIDAR_LOGIN
    (RUT_C  IN  INT,
    PASS_C  IN  VARCHAR)
    RETURNS BOOLEAN AS $$
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
$$ LANGUAGE PLPGSQL;

/****************************************************************************************************************************************************************/

SELECT * from CALCULO_TOTAL(TO_DATE('02/10/2018','DD/MM/YYYY'),TO_DATE('30/09/2018','DD/MM/YYYY'),1,8990);
DROP FUNCTION CALCULO_TOTAL(DATE,DATE,INT,INT);
CREATE OR REPLACE FUNCTION CALCULO_TOTAL(
    FECHA_F IN DATE,
    FECHA_I IN DATE,
    CANTIDAD IN INT,
    TOTAL IN INT)
    RETURNS INT AS $$
DECLARE
    SUMADOR INT := 0;
BEGIN    
    SUMADOR := (TOTAL*CAST(DIFERENCIA AS INT));
    RETURN SUMADOR;
END;
$$ LANGUAGE PLPGSQL;

/******************************************************************************************************************************************************************/
select bool,message from verificar_comuna(185756203,7,7401);
drop function verificar_comuna(int,int,int);
CREATE OR REPLACE FUNCTION VERIFICAR_COMUNA(
    RUT_U IN USUARIO.RUT%TYPE,
    REGION_D IN REGION.REGION_ID%TYPE,
    COMUNA_D IN COMUNA.COMUNA_ID%TYPE,
    BOOL OUT VARCHAR,
    MESSAGE OUT VARCHAR)
RETURNS RECORD AS $$
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
$$ LANGUAGE PLPGSQL;