select bool,message from insertar_usuario(97784507,'felipe','tapia','ftapia46@gmail.com','pipemax','direccion',986520661);
select bool,message from eliminar_usuario(86931133);
select bool,message from actualizar_user(185756203,'felipe','tapia','ftapia46@gmail.com','direccion',986520661);
select bool,message from actualizar_password_user(185756203,'maximus');
select bool,message from nueva_herramienta(1,'algo','buena herramienta','',1)
select * from usuario;
select * from provincia;
select * from comuna;


select cod_sucursal 
from sucursal_herramienta
group by cod_sucursal
having count(cod_herramienta) > 1;

select re.region_id,re.region_nombre,c.comuna_id 
from region re join provincia pro
on re.region_id = pro.provincia_region_id
join comuna c
on pro.provincia_id = c.comuna_provincia_id
join sucursal su
on c.comuna_id = su.comuna
group by re.region_id

select * from comuna where comuna_id in (7101,7401);

select * from region;

select c.comuna_id, c.comuna_nombre 
from region r
join provincia p
on r.region_id = p.provincia_region_id
join comuna c
on p.provincia_id = c.comuna_provincia_id
where r.region_id = 13;

select * from arriendo WHERE fecha_inicio between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY')
                            OR fecha_final between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY');
select * from detalle;
select * from carrito;
select * from arriendo where fecha_arriendo = CAST(NOW() as DATE);
SELECT C.cod_herramienta, H.nombre, H.url_foto, C.cantidad, SH.stock, C.total, 
            verificar_producto_venta('09/10/2018','10/10/2018',C.cod_sucursal,C.cod_herramienta) as DISPONIBILIDAD,
            SH.precio FROM carrito C
            JOIN herramienta H
            ON C.cod_herramienta = H.cod_herramienta
            JOIN sucursal_herramienta SH
            ON SH.cod_herramienta = H.cod_herramienta
            WHERE SH.cod_sucursal = 2
            AND C.rut = 185756203;
SELECT sum(total) AS total FROM carrito 
        WHERE rut = 185756203
        AND cod_sucursal = 2
        AND cantidad <= verificar_producto_venta('02/10/2018','10/10/2018',cod_sucursal,cod_herramienta);
SELECT COD_HERRAMIENTA,CANTIDAD,COD_SUCURSAL,TOTAL FROM CARRITO 
WHERE RUT = 185756203 AND COD_SUCURSAL = 2;
select * from detalle join arriendo on detalle.id_a=arriendo.cod_arriendo where id_a in (18,23);
select * from sucursal_herramienta where cod_herramienta = 1594265 and cod_sucursal = 2;
select * from region;
select * from comuna where comuna_nombre = 'Talca';
select * from comuna where comuna_nombre = 'Linares';
select * from provincia;
select * from sucursal;
select * from detalle where cod_h = 1594265;

SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, (SH.stock - D.cantidad) AS stock
FROM herramienta H JOIN categoria C 
ON H.cod_categoria=C.cod_categoria
JOIN sucursal_herramienta SH
ON H.cod_herramienta = SH.cod_herramienta
JOIN detalle D 
ON D.cod_h=H.cod_herramienta
WHERE H.cod_herramienta IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                            ON A.cod_arriendo=D.id_a JOIN herramienta H
                            ON D.cod_h=H.cod_herramienta
                            WHERE A.fecha_inicio between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY')
                            OR A.fecha_final between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY'))
AND SH.cod_sucursal = 2 
GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre, SH.stock, stock2




SELECT cod_herramienta, nombre, descripcion, url_foto, precio, nombreC, stock FROM
(SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, (SH.stock - SUM(D.cantidad)) AS stock
FROM herramienta H JOIN categoria C 
ON H.cod_categoria=C.cod_categoria
JOIN sucursal_herramienta SH
ON H.cod_herramienta = SH.cod_herramienta
JOIN detalle D 
ON D.cod_h=H.cod_herramienta
WHERE D.id_a IN (SELECT A.cod_arriendo FROM arriendo A JOIN detalle D 
                            ON A.cod_arriendo=D.id_a JOIN herramienta H
                            ON D.cod_h=H.cod_herramienta
                            WHERE A.fecha_inicio between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY')
                            OR A.fecha_final between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY'))
AND SH.cod_sucursal = 2 
GROUP BY H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre, SH.stock
UNION 
SELECT H.cod_herramienta, H.nombre, H.descripcion, H.url_foto, SH.precio, C.nombre AS nombreC, SH.stock AS stock
FROM herramienta H JOIN categoria C 
ON H.cod_categoria=C.cod_categoria
JOIN sucursal_herramienta SH
ON H.cod_herramienta = SH.cod_herramienta
FULL OUTER JOIN detalle D 
ON D.cod_h=H.cod_herramienta
WHERE H.cod_herramienta NOT IN (SELECT H.cod_herramienta FROM arriendo A JOIN detalle D 
                            ON A.cod_arriendo=D.id_a JOIN herramienta H
                            ON D.cod_h=H.cod_herramienta
                            WHERE A.fecha_inicio between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY')
                            OR A.fecha_final between to_date('12/10/2018', 'DD/MM/YYYY') AND to_date('13/10/2018', 'DD/MM/YYYY'))
AND SH.cod_sucursal = 2) as RETORNO
order by stock ".$stock.",precio ".$precio."
limit ".$limite->limite."
offset ".$limite->offset."


select bool,message from nueva_categoria('Carpinteria');
select bool,message from nueva_categoria('Ccompactacion');
select bool,message from nueva_categoria('Obra Gruesa');
select bool,message from nueva_categoria('Demolicion');
select bool,message from nueva_categoria('Aseo');
select * from categoria;


select bool,message from nueva_empresa(96792430,'Sodimac S.A.');
select * from empresa;

select bool,message from nueva_sucursal('Sodimac Talca','Av San Miguel n°12',987654321,'',96792430,7101);
select bool,message from nueva_sucursal('Sodimac Linares','Av. Aníbal León Bustos 0376',123456789,'',96792430,7401);
select * from sucursal;


select * from herramienta;
select bool,message from nueva_herramienta(930385,'Cincelador eléctrico 8,7 kg, makita','demolición o confeccion de surcos en concretos menores.','cincel.jpg',4);
select bool,message from nueva_herramienta(1613774,'Demoledor eléctrico 9,2 kg, makita','demolición de muros y radieres.','demoledor.jpg',4);
select bool,message from nueva_herramienta(1676326,'Demoledor eléctrico 17,0 kg, makita','demolición de muros y radieres.','demoledor2.jpg',4);
select bool,message from nueva_herramienta(513962,'Demoledor eléctrico 32 kg, makita','demolición de muros, cimientos o pavimentos.','demoledor3.jpg',4);

select bool,message from nueva_herramienta(2646528,'alisador de hormigón 36 pulgadas 9,0 hp, wacker','para alisado de superficies de hormigón fresco, hasta 2 horas de aplicado.','alisador.jpg',3);
select bool,message from nueva_herramienta(2646595,'cortadora de pavimento 14 hp, wacker','corte de asfaltos y pavimentos con disco de 14 y 16 pulgadas.','cortadora.jpg',3);
select bool,message from nueva_herramienta(3087158,'trompo elÉctrico 150 litros, tecnamaq','trompo de volteo directo para la elaboración de mezclas de hormigón y morteros. capacidad máxima 150 litros. motor 1,5 hp de 220 volts. apto para trabajos mayores de construcción.','trompo.jpg',3);
select bool,message from nueva_herramienta(2738831,'trompo elÉctrico 350 litros 2hp, emaresa','trompo de volteo directo para la elaboración de mezclas de hormigón y morteros. capacidad máxima 350 litros. motor 2 hp de 220 volts. mecanismo de seguridad, sin cremalleras expuestas.','trompo2.jpg',3);

select bool,message from nueva_herramienta(2646684,'placa compactadora 2000 kn, wacker','ccompactación de terrenos granulares de mediana densidad.','compactadora.jpg',2);
select bool,message from nueva_herramienta(2646692,'placa compactadora 2500 kn, wacker','ccompactación de terrenos granulares de alta densidad.','compactadora2.jpg',2);
select bool,message from nueva_herramienta(1953079,'rodillo compactador 4000 kn, bomag','compactación de asfaltos y terrenos granulares en extensión.','rodillo.jpg',2);
select bool,message from nueva_herramienta(2646781,'vibropisÓn diesel 4,1 hp, wacker','compactación de terrenos granulares reducidos, especialmente zanjas.','vibropison.jpg',2);

select bool,message from nueva_herramienta(932965,'lijadora orbital 600 w, makita','trabajos de lijado en carpintería y mueblería fina.','lijadora.jpg',1);
select bool,message from nueva_herramienta(933600,'cepillo elÉctrico 155 mm, makita','cepillado y alisado de maderas (palos y tablas) sobre 5 pulgadas de ancho.','cepillo.jpg',1);
select bool,message from nueva_herramienta(1878141,'cfresadora industrial 8 mm, makita','fresado y moldeado en bordes de maderas y superficies de tableros.','fresadora.jpg',1);
select bool,message from nueva_herramienta(933181,'lijadora de pulido 1250 w, makita','trabajos de pulido en carpintería, construcción y vehículos.','pulido.jpg',1);
select bool,message from nueva_herramienta(1594265,'sierra circular 185 mm, makita','corte de maderas (palos y tableros) hasta 3 pulgadas.','circular.jpg',1);

select bool,message from nueva_herramienta(1791680,'aspiradora industrial 2 motores 2000 w, luster','para aspirado de polvo y agua y residuos ligeros.','aspiradora.jpg',5);
select bool,message from nueva_herramienta(1791699,'aspiradora semi-industrial a motor 1000 w, luster','para aspirado de polvo y agua y residuos ligeros.','aspiradora2.jpg',5);
select bool,message from nueva_herramienta(2619407,'hidrolavadora elÉctrica de agua frÍa 3,9 hp, karcher','lavado a presión de vehículos y superficies.','hidrolavadora.jpg',5);
select bool,message from nueva_herramienta(1791710,'lavadora de alfombras y pisos duros 1200 w, luster, karcher','lavadora industrial de alfombras de pisos duros (cerámicos, baldosas, etc.).','lavadora.jpg',5);


select bool, message from vincular_herramienta_sucursal(930385,1,7690,5);
select bool, message from vincular_herramienta_sucursal(930385,2,7990,2);
select bool, message from vincular_herramienta_sucursal(1613774,1,6490,7);
select bool, message from vincular_herramienta_sucursal(1613774,2,5900,12);
select bool, message from vincular_herramienta_sucursal(1676326,1,9290,2);
select bool, message from vincular_herramienta_sucursal(1676326,2,9990,1);
select bool, message from vincular_herramienta_sucursal(513962,2,10500,5);

select bool, message from vincular_herramienta_sucursal(2646528,1,14390,12);
select bool, message from vincular_herramienta_sucursal(2646528,2,15490,3);
select bool, message from vincular_herramienta_sucursal(2646595,1,13290,3);
select bool, message from vincular_herramienta_sucursal(2646595,2,13990,1);
select bool, message from vincular_herramienta_sucursal(3087158,1,6590,5);
select bool, message from vincular_herramienta_sucursal(3087158,2,6590,5);
select bool, message from vincular_herramienta_sucursal(2738831,1,17290,2);

select bool, message from vincular_herramienta_sucursal(2646684,1,7090,4);
select bool, message from vincular_herramienta_sucursal(2646684,2,7290,1);
select bool, message from vincular_herramienta_sucursal(2646692,2,11790,2);
select bool, message from vincular_herramienta_sucursal(1953079,1,22890,2);
select bool, message from vincular_herramienta_sucursal(1953079,2,21890,1);
select bool, message from vincular_herramienta_sucursal(2646781,1,11590,5);

select bool, message from vincular_herramienta_sucursal(932965,1,4590,6);
select bool, message from vincular_herramienta_sucursal(932965,2,4590,3);
select bool, message from vincular_herramienta_sucursal(933600,1,5390,2);
select bool, message from vincular_herramienta_sucursal(933600,2,5390,3);
select bool, message from vincular_herramienta_sucursal(1878141,1,5090,1);
select bool, message from vincular_herramienta_sucursal(1878141,2,5390,1);
select bool, message from vincular_herramienta_sucursal(933181,1,4290,6);
select bool, message from vincular_herramienta_sucursal(933181,2,4390,5);
select bool, message from vincular_herramienta_sucursal(1594265,2,4090,2);
select bool, message from vincular_herramienta_sucursal(1594265,1,4290,3);

select bool, message from vincular_herramienta_sucursal(1791680,1,8090,3);
select bool, message from vincular_herramienta_sucursal(1791699,1,5590,2);
select bool, message from vincular_herramienta_sucursal(2619407,1,8190,5);
select bool, message from vincular_herramienta_sucursal(1791710,1,8990,1);

insert into region(region_id, region_nombre) values(1, 'tarapacá');
insert into region(region_id, region_nombre) values(2, 'antofagasta');
insert into region(region_id, region_nombre) values(3, 'atacama');
insert into region(region_id, region_nombre) values(4, 'coquimbo');
insert into region(region_id, region_nombre) values(5, 'valparaíso');
insert into region(region_id, region_nombre) values(6, 'región del libertador gral. bernardo o''higgins');
insert into region(region_id, region_nombre) values(7, 'región del maule');
insert into region(region_id, region_nombre) values(8, 'región del biobío');
insert into region(region_id, region_nombre) values(9, 'región de la araucanía');
insert into region(region_id, region_nombre) values(10, 'región de los lagos');
insert into region(region_id, region_nombre) values(11, 'región aisén del gral. carlos ibáñez del campo');
insert into region(region_id, region_nombre) values(12, 'región de magallanes y de la antártica chilena');
insert into region(region_id, region_nombre) values(13, 'región metropolitana de santiago');
insert into region(region_id, region_nombre) values(14, 'región de los ríos');
insert into region(region_id, region_nombre) values(15, 'arica y parinacota');
insert into region(region_id, region_nombre) values(16, 'región de Ñuble');

insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (11, 'iquique', 1);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (14, 'tamarugal', 1);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (21, 'antofagasta', 2);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (22, 'el loa', 2);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (23, 'tocopilla', 2);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (31, 'copiapó', 3);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (32, 'chañaral', 3);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (33, 'huasco', 3);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (41, 'elqui', 4);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (42, 'choapa', 4);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (43, 'limarí', 4);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (51, 'valparaíso', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (52, 'isla de pascua', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (53, 'los andes', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (54, 'petorca', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (55, 'quillota', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (56, 'san antonio', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (57, 'san felipe de aconcagua', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (58, 'marga marga', 5);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (61, 'cachapoal', 6);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (62, 'cardenal caro', 6);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (63, 'colchagua', 6);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (71, 'talca', 7);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (72, 'cauquenes', 7);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (73, 'curicó', 7);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (74, 'linares', 7);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (81, 'concepción', 8);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (82, 'arauco', 8);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (83, 'biobío', 8);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (91, 'cautín', 9);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (92, 'malleco', 9);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (101, 'llanquihue', 10);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (102, 'chiloé', 10);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (103, 'osorno', 10);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (104, 'palena', 10);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (111, 'coihaique', 11);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (112, 'aisén', 11);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (113, 'capitán prat', 11);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (114, 'general carrera', 11);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (121, 'magallanes', 12);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (122, 'antártica chilena', 12);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (123, 'tierra del fuego', 12);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (124, 'Última esperanza', 12);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (131, 'santiago', 13);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (132, 'cordillera', 13);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (133, 'chacabuco', 13);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (134, 'maipo', 13);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (135, 'melipilla', 13);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (136, 'talagante', 13);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (141, 'valdivia', 14);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (142, 'ranco', 14);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (151, 'arica', 15);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (152, 'parinacota', 15);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (163, 'diguillín', 16);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (162, 'itata', 16);
insert into provincia(provincia_id, provincia_nombre, provincia_region_id) values (161, 'punilla', 16);

insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1101, 'iquique', 11);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1107, 'alto hospicio', 11);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1401, 'pozo almonte', 14);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1402, 'camiña', 14);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1403, 'colchane', 14);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1404, 'huara', 14);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (1405, 'pica', 14);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2101, 'antofagasta', 21);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2102, 'mejillones', 21);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2103, 'sierra gorda', 21);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2104, 'taltal', 21);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2201, 'calama', 22);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2202, 'ollagüe', 22);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2203, 'san pedro de atacama', 22);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2301, 'tocopilla', 23);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (2302, 'maría elena', 23);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3101, 'copiapó', 31);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3102, 'caldera', 31);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3103, 'tierra amarilla', 31);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3201, 'chañaral', 32);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3202, 'diego de almagro', 32);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3301, 'vallenar', 33);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3302, 'alto del carmen', 33);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3303, 'freirina', 33);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (3304, 'huasco', 33);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4101, 'la serena', 41);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4102, 'coquimbo', 41);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4103, 'andacollo', 41);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4104, 'la higuera', 41);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4105, 'paihuano', 41);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4106, 'vicuña', 41);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4201, 'illapel', 42);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4202, 'canela', 42);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4203, 'los vilos', 42);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4204, 'salamanca', 42);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4301, 'ovalle', 43);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4302, 'combarbalá', 43);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4303, 'monte patria', 43);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4304, 'punitaqui', 43);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (4305, 'río hurtado', 43);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5101, 'valparaíso', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5102, 'casablanca', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5103, 'concón', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5104, 'juan fernández', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5105, 'puchuncaví', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5107, 'quintero', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5109, 'viña del mar', 51);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5201, 'isla de pascua', 52);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5301, 'los andes', 53);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5302, 'calle larga', 53);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5303, 'rinconada', 53);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5304, 'san esteban', 53);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5401, 'la ligua', 54);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5402, 'cabildo', 54);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5403, 'papudo', 54);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5404, 'petorca', 54);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5405, 'zapallar', 54);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5501, 'quillota', 55);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5502, 'la calera', 55);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5503, 'hijuelas', 55);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5504, 'la cruz', 55);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5506, 'nogales', 55);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5601, 'san antonio', 56);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5602, 'algarrobo', 56);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5603, 'cartagena', 56);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5604, 'el quisco', 56);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5605, 'el tabo', 56);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5606, 'santo domingo', 56);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5701, 'san felipe', 57);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5702, 'catemu', 57);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5703, 'llay llay', 57);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5704, 'panquehue', 57);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5705, 'putaendo', 57);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5706, 'santa maría', 57);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5801, 'quilpué', 58);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5802, 'limache', 58);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5803, 'olmué', 58);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (5804, 'villa alemana', 58);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6101, 'rancagua', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6102, 'codegua', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6103, 'coinco', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6104, 'coltauco', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6105, 'doñihue', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6106, 'graneros', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6107, 'las cabras', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6108, 'machalí', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6109, 'malloa', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6110, 'mostazal', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6111, 'olivar', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6112, 'peumo', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6113, 'pichidegua', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6114, 'quinta de tilcoco', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6115, 'rengo', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6116, 'requínoa', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6117, 'san vicente', 61);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6201, 'pichilemu', 62);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6202, 'la estrella', 62);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6203, 'litueche', 62);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6204, 'marchihue', 62);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6205, 'navidad', 62);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6206, 'paredones', 62);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6301, 'san fernando', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6302, 'chépica', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6303, 'chimbarongo', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6304, 'lolol', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6305, 'nancagua', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6306, 'palmilla', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6307, 'peralillo', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6308, 'placilla', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6309, 'pumanque', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (6310, 'santa cruz', 63);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7101, 'talca', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7102, 'constitución', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7103, 'curepto', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7104, 'empedrado', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7105, 'maule', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7106, 'pelarco', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7107, 'pencahue', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7108, 'río claro', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7109, 'san clemente', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7110, 'san rafael', 71);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7201, 'cauquenes', 72);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7202, 'chanco', 72);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7203, 'pelluhue', 72);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7301, 'curicó', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7302, 'hualañé', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7303, 'licantén', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7304, 'molina', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7305, 'rauco', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7306, 'romeral', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7307, 'sagrada familia', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7308, 'teno', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7309, 'vichuquén', 73);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7401, 'linares', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7402, 'colbún', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7403, 'longaví', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7404, 'parral', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7405, 'retiro', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7406, 'san javier', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7407, 'villa alegre', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (7408, 'yerbas buenas', 74);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8101, 'concepción', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8102, 'coronel', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8103, 'chiguayante', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8104, 'florida', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8105, 'hualqui', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8106, 'lota', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8107, 'penco', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8108, 'san pedro de la paz', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8109, 'santa juana', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8110, 'talcahuano', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8111, 'tomé', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8112, 'hualpén', 81);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8201, 'lebu', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8202, 'arauco', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8203, 'cañete', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8204, 'contulmo', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8205, 'curanilahue', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8206, 'los Álamos', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8207, 'tirúa', 82);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8301, 'los Ángeles', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8302, 'antuco', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8303, 'cabrero', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8304, 'laja', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8305, 'mulchén', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8306, 'nacimiento', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8307, 'negrete', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8308, 'quilaco', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8309, 'quilleco', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8310, 'san rosendo', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8311, 'santa bárbara', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8312, 'tucapel', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8313, 'yumbel', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8314, 'alto biobío', 83);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9101, 'temuco', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9102, 'carahue', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9103, 'cunco', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9104, 'curarrehue', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9105, 'freire', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9106, 'galvarino', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9107, 'gorbea', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9108, 'lautaro', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9109, 'loncoche', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9110, 'melipeuco', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9111, 'nueva imperial', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9112, 'padre las casas', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9113, 'perquenco', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9114, 'pitrufquén', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9115, 'pucón', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9116, 'saavedra', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9117, 'teodoro schmidt', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9118, 'toltén', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9119, 'vilcún', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9120, 'villarrica', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9121, 'cholchol', 91);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9201, 'angol', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9202, 'collipulli', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9203, 'curacautín', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9204, 'ercilla', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9205, 'lonquimay', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9206, 'los sauces', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9207, 'lumaco', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9208, 'purén', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9209, 'renaico', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9210, 'traiguén', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (9211, 'victoria', 92);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10101, 'puerto montt', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10102, 'calbuco', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10103, 'cochamó', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10104, 'fresia', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10105, 'frutillar', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10106, 'los muermos', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10107, 'llanquihue', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10108, 'maullín', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10109, 'puerto varas', 101);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10201, 'castro', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10202, 'ancud', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10203, 'chonchi', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10204, 'curaco de vélez', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10205, 'dalcahue', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10206, 'puqueldón', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10207, 'queilén', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10208, 'quellón', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10209, 'quemchi', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10210, 'quinchao', 102);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10301, 'osorno', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10302, 'puerto octay', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10303, 'purranque', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10304, 'puyehue', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10305, 'río negro', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10306, 'san juan de la costa', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10307, 'san pablo', 103);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10401, 'chaitén', 104);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10402, 'futaleufú', 104);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10403, 'hualaihué', 104);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (10404, 'palena', 104);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11101, 'coyhaique', 111);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11102, 'lago verde', 111);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11201, 'aysén', 112);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11202, 'cisnes', 112);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11203, 'guaitecas', 112);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11301, 'cochrane', 113);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11302, 'o''higgins', 113);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11303, 'tortel', 113);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11401, 'chile chico', 114);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (11402, 'río ibáñez', 114);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12101, 'punta arenas', 121);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12102, 'laguna blanca', 121);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12103, 'río verde', 121);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12104, 'san gregorio', 121);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12201, 'cabo de hornos', 122);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12202, 'antártica', 122);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12301, 'porvenir', 123);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12302, 'primavera', 123);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12303, 'timaukel', 123);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12401, 'natales', 124);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (12402, 'torres del paine', 124);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13101, 'santiago', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13102, 'cerrillos', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13103, 'cerro navia', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13104, 'conchalí', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13105, 'el bosque', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13106, 'estación central', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13107, 'huechuraba', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13108, 'independencia', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13109, 'la cisterna', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13110, 'la florida', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13111, 'la granja', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13112, 'la pintana', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13113, 'la reina', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13114, 'las condes', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13115, 'lo barnechea', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13116, 'lo espejo', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13117, 'lo prado', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13118, 'macul', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13119, 'maipú', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13120, 'Ñuñoa', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13121, 'pedro aguirre cerda', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13122, 'peñalolén', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13123, 'providencia', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13124, 'pudahuel', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13125, 'quilicura', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13126, 'quinta normal', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13127, 'recoleta', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13128, 'renca', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13129, 'san joaquín', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13130, 'san miguel', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13131, 'san ramón', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13132, 'vitacura', 131);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13201, 'puente alto', 132);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13202, 'pirque', 132);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13203, 'san josé de maipo', 132);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13301, 'colina', 133);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13302, 'lampa', 133);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13303, 'tiltil', 133);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13401, 'san bernardo', 134);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13402, 'buin', 134);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13403, 'calera de tango', 134);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13404, 'paine', 134);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13501, 'melipilla', 135);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13502, 'alhué', 135);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13503, 'curacaví', 135);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13504, 'maría pinto', 135);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13505, 'san pedro', 135);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13601, 'talagante', 136);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13602, 'el monte', 136);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13603, 'isla de maipo', 136);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13604, 'padre hurtado', 136);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (13605, 'peñaflor', 136);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14101, 'valdivia', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14102, 'corral', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14103, 'lanco', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14104, 'los lagos', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14105, 'máfil', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14106, 'mariquina', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14107, 'paillaco', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14108, 'panguipulli', 141);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14201, 'la unión', 142);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14202, 'futrono', 142);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14203, 'lago ranco', 142);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (14204, 'río bueno', 142);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (15101, 'arica', 151);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (15102, 'camarones', 151);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (15201, 'putre', 152);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (15202, 'general lagos', 152);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8401, 'chillán', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8402, 'bulnes', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8406, 'chillán viejo', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8407, 'el carmen', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8410, 'pemuco', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8411, 'pinto', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8413, 'quillón', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8418, 'san ignacio', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8421, 'yungay', 163);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8403, 'cobquecura', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8404, 'coelemu', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8408, 'ninhue', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8412, 'portezuelo', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8414, 'quirihue', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8415, 'ránquil', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8420, 'treguaco', 162);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8405, 'coihueco', 161);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8409, 'Ñiquén', 161);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8416, 'san carlos', 161);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8417, 'san fabián', 161);
insert into comuna(comuna_id, comuna_nombre, comuna_provincia_id) values (8419, 'san nicolás', 161);