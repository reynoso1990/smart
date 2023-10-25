<template>
    <div>
            <!-- Theme Settings Modal Start -->
        <div
          class="modal fade modal-right scroll-out-negative"
          id="settings"
          data-bs-backdrop="true"
          tabindex="-1"
          role="dialog"
          aria-labelledby="settings"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-dialog-scrollable full" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Ajustes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <div class="scroll-track-visible">
                  <div class="mb-5" id="color">
                    <label class="mb-3 d-inline-block form-label">Color</label>
                    <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="light-blue" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="blue-light"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Azul claro</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-blue" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="blue-dark"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Azul oscuro</span>
                        </div>
                      </a>
                    </div>

                    <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="light-red" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="red-light"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Rojo claro</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-red" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="red-dark"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Rojo oscuro</span>
                        </div>
                      </a>
                    </div>

                    <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="light-green" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="green-light"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Verde claro</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-green" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="green-dark"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Verde Oscuro</span>
                        </div>
                      </a>
                    </div>

                    <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="light-purple" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="purple-light"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Morado claro</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-purple" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="purple-dark"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Morado oscuro</span>
                        </div>
                      </a>
                    </div>

                    <div class="row d-flex g-3 justify-content-between flex-wrap mb-3">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="light-pink" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="pink-light"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Rosado claro</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="dark-pink" data-parent="color">
                        <div class="card rounded-md p-3 mb-1 no-shadow color">
                          <div class="pink-dark"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Rosado oscuro</span>
                        </div>
                      </a>
                    </div>
                  </div>

                  <div class="mb-5" id="navcolor">
                    <label class="mb-3 d-inline-block form-label">Navegación</label>
                    <div class="row d-flex g-3 justify-content-between flex-wrap">
                      <a href="#" class="flex-grow-1 w-33 option col" data-value="default" data-parent="navcolor">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Por defecto</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-33 option col" data-value="light" data-parent="navcolor">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-secondary figure-light top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Claro</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-33 option col" data-value="dark" data-parent="navcolor">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-muted figure-dark top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Oscuro</span>
                        </div>
                      </a>
                    </div>
                  </div>

                  <div class="mb-5" id="placement">
                    <label class="mb-3 d-inline-block form-label">Ubicación de Menú </label>
                    <div class="row d-flex g-3 justify-content-between flex-wrap">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="horizontal" data-parent="placement">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Horizontal</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="vertical" data-parent="placement">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary left"></div>
                          <div class="figure figure-secondary right"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Vertical</span>
                        </div>
                      </a>
                    </div>
                  </div>

                  <div class="mb-5" id="behaviour">
                    <label class="mb-3 d-inline-block form-label">Comportamiento de Menu</label>
                    <div class="row d-flex g-3 justify-content-between flex-wrap">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="pinned" data-parent="behaviour">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary left large"></div>
                          <div class="figure figure-secondary right small"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Extendido</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="unpinned" data-parent="behaviour">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary left"></div>
                          <div class="figure figure-secondary right"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Contraido</span>
                        </div>
                      </a>
                    </div>
                  </div>

                  <div class="mb-5" id="layout">
                    <label class="mb-3 d-inline-block form-label">Disposición</label>
                    <div class="row d-flex g-3 justify-content-between flex-wrap">
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="fluid" data-parent="layout">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Completo</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-50 option col" data-value="boxed" data-parent="layout">
                        <div class="card rounded-md p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom small"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">En caja</span>
                        </div>
                      </a>
                    </div>
                  </div>

                  <div class="mb-5" id="radius">
                    <label class="mb-3 d-inline-block form-label">Bordes</label>
                    <div class="row d-flex g-3 justify-content-between flex-wrap">
                      <a href="#" class="flex-grow-1 w-33 option col" data-value="rounded" data-parent="radius">
                        <div class="card rounded-md radius-rounded p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Redondeado</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-33 option col" data-value="standard" data-parent="radius">
                        <div class="card rounded-md radius-regular p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Estándar</span>
                        </div>
                      </a>
                      <a href="#" class="flex-grow-1 w-33 option col" data-value="flat" data-parent="radius">
                        <div class="card rounded-md radius-flat p-3 mb-1 no-shadow">
                          <div class="figure figure-primary top"></div>
                          <div class="figure figure-secondary bottom"></div>
                        </div>
                        <div class="text-muted text-part">
                          <span class="text-extra-small align-middle">Recto</span>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button type="button" class="btn settings-button btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#settings" id="settingsButton">
          <i data-cs-icon="paint-roller" class="position-relative"></i>
        </button>
     </div>
</template>