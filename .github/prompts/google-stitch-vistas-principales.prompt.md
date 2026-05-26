---
description: "Genera un brief listo para Google Stitch para diseñar las vistas publicas principales de CB Tiendas: inicio, exploracion de negocios, detalle, categorias y sobre nosotros."
name: "Google Stitch CB Tiendas"
argument-hint: "Enfoque opcional, por ejemplo: prioriza visitantes y conversion de emprendedores"
agent: "agent"
---

Redacta un unico prompt, en espanol y listo para pegar directamente en Google Stitch, para disenar las vistas publicas principales de CB Tiendas.

Usa como fuente de verdad:
- [Descripcion de roles y objetivos](../project/roles-description.md)
- [Rutas publicas actuales](../../routes/web.php)
- [Modelo Store](../../app/Models/Store.php)
- [Modelo Category](../../app/Models/Category.php)
- [Vista de inicio actual](../../resources/views/welcome.blade.php)
- [Vista publica de tiendas actual](../../resources/views/tienda.blade.php)
- [Vista publica de categorias actual](../../resources/views/categorias.blade.php)

Integra este enfoque adicional solo si aporta valor:
${input:enfoque opcional, por ejemplo "prioriza descubrimiento de negocios y una estetica calida local"}

Requisitos para el prompt que vas a entregar:
- Devuelve solo el prompt final para Google Stitch. No agregues explicacion, prefacio ni notas fuera del prompt.
- El prompt debe pedir un diseno web multipagina, no un panel administrativo ni una SPA.
- Debe alinear el diseno con un proyecto Laravel 13 con vistas Blade y navegacion publica tradicional.
- Debe describir el producto como un repositorio digital de emprendedores locales de Coronel Bogado, orientado a visibilidad, descubrimiento y contacto.
- Debe priorizar al visitante, sin perder un CTA claro para que nuevos emprendedores registren su negocio.
- Debe solicitar microcopy en espanol.
- Debe incluir, como minimo, estas vistas:
  - Inicio o landing
  - Listado o exploracion de negocios
  - Detalle del negocio
  - Listado o exploracion por categorias
  - Sobre nosotros
  - Contacto o llamada a registrar un emprendimiento
- Para cada vista, el prompt debe especificar objetivo, secciones principales, componentes clave y CTAs.
- En la vista de detalle del negocio, el prompt debe incluir contenido basado en el modelo Store: nombre, portada, logo, descripcion, categorias, direccion, telefono, correo, sitio web, ubicacion en mapa y estado destacada cuando corresponda.
- En las vistas de exploracion, el prompt debe incluir busqueda, filtros por categoria, tarjetas de negocio, estados vacios y jerarquia clara para descubrimiento.
- El prompt debe pedir un sistema visual consistente entre vistas: header, footer, navegacion, cards, chips de categorias, botones, formularios y bloques informativos reutilizables.
- El tono visual debe ser cercano, comunitario, confiable y contemporaneo; evitar estetica de SaaS generico, dashboards oscuros o identidad demasiado corporativa.
- Debe pedir diseno responsive para movil y desktop, con accesibilidad basica, buen contraste y llamadas a la accion claras.
- Debe mantener espacio para crecimiento futuro, como valoraciones, mensajeria o redes sociales, sin hacer que esas funciones dominen el diseno actual.
- Si falta algun dato, el prompt puede pedir a Stitch que haga supuestos prudentes y consistentes con un marketplace local de negocios.

Estructura sugerida del prompt final para Google Stitch:
1. Contexto del producto
2. Objetivo del diseno
3. Publico principal y secundarios
4. Vistas obligatorias
5. Sistema visual y tono
6. Restricciones de producto y UX
7. Resultado esperado
