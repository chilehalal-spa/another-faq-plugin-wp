# Another FAQ Plugin

Plugin de WordPress para preguntas frecuentes. Agrega el tipo de contenido
**FAQ** (con categorías) y el shortcode `[faq]` para mostrarlas en acordeón.

Se usa en [chilehalal.cl](https://www.chilehalal.cl).

## Instalación

1. Descarga `another-faq-plugin.zip` desde la
   [última release](https://github.com/chilehalal-spa/another-faq-plugin-wp/releases/latest).
   No uses el botón "Code → Download ZIP": ese zip trae otro nombre de carpeta.
2. En WordPress: *Plugins → Añadir nuevo → Subir plugin*, elige el zip y activa.

Requiere WordPress 5.8 o superior y PHP 7.4 o superior.

## Uso

Crea las preguntas en *Preguntas Frecuentes* del menú de administración y
agrúpalas en *Categorías*. Luego inserta el shortcode en cualquier página o
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

## Actualizaciones

El plugin se actualiza desde las releases de este repositorio, como cualquier
plugin de wordpress.org: aparecen en *Escritorio → Actualizaciones* y se pueden
dejar automáticas en *Plugins → Activar actualizaciones automáticas*.

WordPress revisa cada 12 horas; la respuesta de GitHub se guarda 6 horas.

## Publicar una versión nueva

1. Sube `Version:` en `another-faq-plugin.php` (por ejemplo `1.2.0`).
2. Haz commit y push a `main`.
3. Crea y sube el tag con el mismo número:

   ```bash
   git tag v1.2.0
   git push origin v1.2.0
   ```

La Action [Release](.github/workflows/release.yml) verifica que el tag coincida
con la versión, arma `another-faq-plugin.zip` y publica la release. Desde ese
momento los sitios ven la actualización.

## Cambios

- **1.1.1**: historial de cambios en el README (prueba del actualizador).
- **1.1.0**: actualizaciones automáticas desde las releases de GitHub, licencia
  GPL-2.0-or-later y README.
- **1.0**: versión inicial (preguntas frecuentes con categorías y shortcode `[faq]`).

## Licencia

GPL-2.0-or-later. Ver [LICENSE](LICENSE).
