<aside class="contenedor-proyectos">
    <div class="panel crear-proyecto">
        <a href="#" class="boton">Nuevo Proyecto <i class="fas fa-plus"></i> </a>
    </div>

    <div class="panel lista-proyectos">
        <h2>Proyectos</h2>
        <ul id="proyectos">
            <?php
                  $proyectos = obtenerProyectos();
                  if ($proyectos) {
                     foreach ($proyectos as $proyecto) { ?>
                    <div class="acciones-proyectos"  id="<?php echo $proyecto['id_proyecto'] ?>">
                            <li>
                              <a href="index.php?id_proyecto=<?php echo $proyecto['id_proyecto'] ?>" id="proyecto:<?php echo $proyecto['id_proyecto'] ?>">
                                    <?php echo $proyecto['nombre']; ?>
                              </a>
                                <i class="orden-icon-eliminar fas fa-trash"></i>
                            </li>
                    </div>
            <?php   }
                  }
                  ?>
        </ul>
    </div>
</aside>
