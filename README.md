# Another FAQ Plugin

Plugin de WordPress para preguntas frecuentes. Agrega el tipo de contenido
**FAQ** (con categorías) y el shortcode `[faq]` para mostrarlas en acordeón.

Se usa en [chilehalal.cl](https://www.chilehalal.cl).

## Instalación

1. Descargue `another-faq-plugin.zip` desde la
   [última release](https://github.com/chilehalal-spa/another-faq-plugin-wp/releases/latest).
   No use el botón "Code → Download ZIP": ese zip trae otro nombre de carpeta.
2. En WordPress: *Plugins → Añadir nuevo → Subir plugin*, elija el zip y actívelo.

Requiere WordPress 5.8 o superior y PHP 7.4 o superior.

## Uso

Cree las preguntas en *Preguntas Frecuentes* del menú de administración y
agrúpelas en *Categorías*. Luego inserte el shortcode en cualquier página o
entrada:

| Shortcode | Muestra |
|---|---|
| `[faq]` | Todas las preguntas |
| `[faq categoria="slug"]` | Solo las de una categoría |
| `[faq id="123"]` | Una sola pregunta (la columna "Shortcode" del listado la entrega lista para copiar) |

Otros atributos:

| Atributo | Valores | Por defecto |
|---|---|---|
| `limite` | número máximo de preguntas | `-1` (todas) |
| `orden` | campo para ordenar (`menu_order`, `title`, `date`…) | `menu_order` |
| `direccion` | `ASC` o `DESC` | `ASC` |
| `exclusivo` | `si`: al abrir una pregunta se cierran las demás | `si` |

### Dos columnas con contacto

Con `titulo`, el FAQ se arma en dos columnas: a la izquierda etiqueta, título,
texto y un botón de contacto; a la derecha las preguntas. En celular se apila.

```
[faq titulo="Resolvemos sus dudas sobre la certificación" whatsapp="si"]
```

| Atributo | Valores | Por defecto |
|---|---|---|
| `titulo` | título de la columna izquierda; activa las dos columnas | vacío (solo acordeón) |
| `etiqueta` | texto pequeño sobre el título | `Preguntas frecuentes` |
| `texto` | párrafo bajo el título | `¿No encuentra su respuesta? Nuestro equipo le responde directamente.` |
| `boton` | texto del botón | `Contacte con un profesional` |
| `whatsapp` | `si` (número del botón flotante del tema) o un número con código de país | vacío |
| `enlace` | cualquier URL para el botón; tiene prioridad sobre `whatsapp` | vacío |

El botón solo aparece si hay `enlace` o `whatsapp`.

### Tarjeta de contacto y cierre

El diseño de dos columnas puede incluir la tarjeta de contacto directo y un
enlace final de evaluación. Ambos son opcionales; los datos de contacto usan
los valores de ChileHalal y se pueden reemplazar con atributos del shortcode:

```
[faq etiqueta="Preguntas frecuentes" titulo="Resolvemos sus dudas sobre la certificación" texto="¿No encuentra su respuesta? Nuestro equipo le responde directamente." whatsapp="si" contacto="si" cierre_enlace="https://www.chilehalal.cl/formulario-certificacion/"]
```

`contacto="si"` muestra el panel con teléfono, correo, horario y nota. Se puede
personalizar mediante `contacto_etiqueta`, `contacto_titulo`, `contacto_texto`,
`telefono`, `correo`, `horario_semana`, `horario_horas`, `horario_viernes`,
`horario_viernes_horas` y `contacto_nota`. Utilice un valor vacío para ocultar
un dato. El teléfono se configura con código de país y se enlaza como `tel:+...`.

El cierre aparece únicamente si se indica `cierre_enlace`; `cierre_titulo`,
`cierre_texto` y `cierre_boton` permiten personalizar su contenido.

### Aspecto y SEO

- Cada pregunta es una tarjeta numerada; la abierta se destaca con fondo y borde.
- Los colores salen de las variables del tema `--color-primary` y
  `--color-danger` si existen (si no, azul y rojo de ChileHalal).
- Las preguntas son `h3` y la página incluye el marcado `FAQPage` (JSON-LD) con
  todas las preguntas mostradas, para que Google pueda usarlas en los resultados.

## Actualizaciones

El plugin se actualiza desde las releases de este repositorio, como cualquier
plugin de wordpress.org: aparecen en *Escritorio → Actualizaciones* y se pueden
dejar automáticas en *Plugins → Activar actualizaciones automáticas*.

WordPress revisa cada 12 horas; la respuesta de GitHub se guarda 6 horas.

## Publicar una versión nueva

El plugin sigue el esquema Mayor.Menor.Parche (`MAJOR.MINOR.PATCH`): el primer
número identifica cambios mayores o refactorizaciones, el segundo nuevas
funcionalidades y el tercero correcciones.

1. Suba `Version:` en `another-faq-plugin.php` (por ejemplo `1.3.0`).
2. Haga commit y push a `main`.
3. Cree y suba el tag con el mismo número:

   ```bash
   git tag v1.3.0
   git push origin v1.3.0
   ```

La Action [Release](.github/workflows/release.yml) verifica que el tag coincida
con la versión, arma `another-faq-plugin.zip` y publica la release. Desde ese
momento los sitios ven la actualización.

## Cambios

- **1.3.0**: tarjeta de contacto opcional y cierre con enlace de evaluación,
  texto unificado en trato formal y enlace telefónico internacional con `+`.
- **1.2.0**: nuevo diseño (tarjetas numeradas, ícono que gira, respuesta
  destacada), modo de dos columnas con botón de contacto, preguntas como `h3` y
  marcado `FAQPage` para Google.
- **1.1.1**: historial de cambios en el README (prueba del actualizador).
- **1.1.0**: actualizaciones automáticas desde las releases de GitHub, licencia
  GPL-2.0-or-later y README.
- **1.0**: versión inicial (preguntas frecuentes con categorías y shortcode `[faq]`).

## Licencia

GPL-2.0-or-later. Ver [LICENSE](LICENSE).
