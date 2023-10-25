<template>
  <div>
    <div class="toggle">
      <a
        href="#"
        class="burger js-menu-toggle"
        data-toggle="collapse"
        data-target="#main-navbar"
      >
        <span></span>
      </a>
    </div>
    <div class="side-inner">
      <div class="profile">
        <img src="images/person_4.jpg" alt="Image" class="img-fluid" />
        <h3 class="name">Usuario</h3>
        <span class="country">Web Designer</span>
      </div>

      <div class="nav-menu">
        <ul>
          <li class="accordion">
            <a href="/dashboard">
              <span class="icon-home mr-3"></span>Dashboard
            </a>
          </li>
          <li class="accordion">
            <a
              href="#"
              data-toggle="collapse"
              data-target="#collapseTwo"
              aria-expanded="false"
              aria-controls="collapseTwo"
              class="collapsible"
            >
              <i class="fas fa-search"></i> Buscar Producto por
            </a>

            <div id="collapseTwo" class="collapse" aria-labelledby="headingOne">
              <div>
                <ul>
                  <li :class="buscar_por == '1' ? 'actives' : ''">
                    <a href="#" @click="back(1)"
                      ><i class="fa fa-list" aria-hidden="true"></i>
                      Descripción</a
                    >
                  </li>
                  <li :class="buscar_por == '2' ? 'actives' : ''">
                    <a href="#" @click="back(2)"
                      ><i class="fa fa-sitemap" aria-hidden="true"></i>
                      Categoria</a
                    >
                  </li>
                  <li :class="buscar_por == '3' ? 'actives' : ''">
                    <a href="#" @click="back(3)"
                      ><i class="fa fa-barcode" aria-hidden="true"></i> Codigo
                      Barra</a
                    >
                  </li>
                </ul>
              </div>
            </div>
          </li>
          <li>
            <a href="#" @click="clickClose()">
              <i class="fas fa-power-off"></i> Salir
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
//

import {
  functions,
  exchangeRate,
} from "../../../../../../../resources/js/mixins/functions";
export default {
  mixins: [functions, exchangeRate],

  data() {
    return {
      place: false,
    };
  },

  methods: {
    back(valor) {
      if (valor == 2) {
        this.place = true;
      } else {
        this.place = false;
        this.$refs.input_item.$el.getElementsByTagName("input")[0].focus();
      }
    },
  },
};
</script>
