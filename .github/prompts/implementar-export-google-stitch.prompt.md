---
description: "Interpreta la exportacion de Google Stitch ya guardada en .github/stitch_design y la implementa en CB Tiendas usando Laravel Blade, rutas web y Tailwind."
name: "Implementar Export Google Stitch"
argument-hint: "Pantallas o alcance opcional, por ejemplo: solo inicio, explorar, detalle y sobre nosotros"
agent: "agent"
---

Implementa en este workspace un diseno ya exportado desde Google Stitch.

Usa como fuente fija de entrada la exportacion ya disponible en .github/stitch_design.

Estructura real conocida de la exportacion:
- [Diseno principal](../stitch_design/luz_de_pueblo/DESIGN.md)
- Subcarpetas de pantallas publicas bajo .github/stitch_design, incluyendo inicio_actualizado_cb_tiendas, explorar_negocios_cb_tiendas, detalle_del_negocio_cb_tiendas, categor_as_cb_tiendas, registrar_emprendimiento_cb_tiendas y sobre_nosotros_cb_tiendas
- Dentro de cada subcarpeta de pantalla, usa code.html y screen.png como referencia

Alcance adicional opcional:
${input:alcance opcional, por ejemplo "solo inicio, explorar, detalle y sobre nosotros"}

Usa como contexto del proyecto:
- [Descripcion de roles y objetivos](../project/roles-description.md)
- [Rutas web actuales](../../routes/web.php)
- [Controlador publico actual](../../app/Http/Controllers/TiendaController.php)
- [Modelo Store](../../app/Models/Store.php)
- [Modelo Category](../../app/Models/Category.php)
- [CSS principal](../../resources/css/app.css)
- [JS principal](../../resources/js/app.js)
- [Vista welcome actual](../../resources/views/welcome.blade.php)
- [Vista de tiendas actual](../../resources/views/tienda.blade.php)
- [Vista de categorias actual](../../resources/views/categorias.blade.php)

Objetivo:
Interpretar correctamente la salida de Stitch y aterrizarla en la arquitectura real del proyecto, implementando las vistas publicas necesarias con Laravel 13, Blade, rutas web y Tailwind CSS 4.

Instrucciones de trabajo:
- Empieza inspeccionando .github/stitch_design y confirma la estructura real antes de editar.
- Toma .github/stitch_design/luz_de_pueblo/DESIGN.md como fuente principal de intencion de producto, sistema visual y estructura global.
- Detecta y mapea las subcarpetas exportadas a vistas reales del proyecto. Si un nombre de carpeta viene deformado o abreviado, infiere la vista correcta usando screen.png y code.html.
- Si falta algun archivo esperado en una subcarpeta, trabaja con lo disponible pero explicita la limitacion.
- Usa las screenshots como referencia visual de fidelidad, espaciado, jerarquia y estados.
- Usa code.html como referencia de layout, copy y estilo, pero no lo copies de forma literal a produccion si eso rompe las convenciones del proyecto.
- Traduce la salida exportada a una implementacion mantenible para este repo: Blade para vistas, rutas web nombradas cuando haga falta, y controladores minimos para cableado publico.
- Mantente dentro del enfoque MVC ya existente. No introduzcas SPA, Inertia, Livewire publico, APIs nuevas ni capas de servicio innecesarias salvo que el material exportado lo requiera de forma justificada.
- Reutiliza y adapta la informacion real del dominio a partir de Store y Category. Si el diseno muestra datos no existentes, haz supuestos prudentes o deja placeholders claramente delimitados.
- Si no se indica alcance, implementa todas las pantallas publicas disponibles en .github/stitch_design que correspondan al sitio publico.
- Si detectas multiples pantallas en el export, mapealas primero a vistas reales del proyecto y decide cuales corresponden a rutas existentes y cuales requieren rutas nuevas.
- Cuando haya elementos repetidos entre pantallas, factoriza en layouts, partials o componentes Blade sencillos en lugar de duplicar markup.
- Convierte estilos generados en clases Tailwind y solo mueve reglas a app.css si de verdad son reutilizables o estructurales.
- Conserva el microcopy en espanol y ajustalo al contexto local de Coronel Bogado cuando sea necesario.
- Si el alcance opcional limita pantallas o entregables, respetalo estrictamente.

Reglas para interpretar la exportacion de Stitch:
- No asumas que code.html es codigo listo para usar; tratalo como prototipo visual.
- No incrustes grandes bloques de CSS o HTML generados si puedes expresarlos mejor con Blade y Tailwind.
- No rompas la navegacion publica existente; extiendela de forma coherente.
- Si el export propone componentes o patrones que chocan con el dominio, prioriza el dominio del proyecto.
- Si hay inconsistencias entre el DESIGN.md principal, los code.html y las screenshots, prioriza en este orden: intencion funcional de DESIGN.md, fidelidad visual de screenshots, detalles estructurales utiles de code.html.

Entregable esperado durante la ejecucion:
- Primero resume brevemente como mapeaste los archivos exportados a pantallas o piezas del proyecto.
- Luego implementa los cambios necesarios en el workspace.
- Al final resume que vistas, rutas, controladores, partials o assets tocaste.
- Ejecuta una validacion enfocada despues de editar: usa la comprobacion mas estrecha disponible para lo que cambiaste y reporta si no pudiste ejecutarla.

Prioridades de implementacion:
1. Fidelidad razonable al diseno exportado.
2. Compatibilidad con la estructura Laravel Blade existente.
3. Mantenibilidad del codigo.
4. Uso correcto de datos reales del proyecto.
5. Responsive y accesibilidad basica.

No pidas una ruta de entrada distinta salvo que la estructura de .github/stitch_design ya no coincida con lo esperado. En ese caso, reporta el desajuste exacto y continua con lo que si exista.
