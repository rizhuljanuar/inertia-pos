<template>
  <Head>
    <title>Edit Role - Aplikasi Kasir</title>
  </Head>
  <main class="c-main">
    <div class="container-fluid">
      <div class="fade-in">
        <div class="row">
          <div class="col-md-12">
            <div class="card border-0 rounded-3 shadow border-top-purple">
              <div class="card-header">
                <span class="font-weight-bold"
                  ><i class="fa fa-shield-alt"></i>EDIT ROLE</span
                >
              </div>
              <div class="card-body">
                <form @submit.prevent="submit">
                  <div class="mb-3">
                    <label for="" class="fw-bold">Role Name</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.name"
                      :class="{ 'is-invalid': errors.name }"
                      placeholder="Role Name"
                    />
                    <div class="alert alert-danger" v-if="errors.name">
                      {{ errors.name }}
                    </div>
                  </div>
                  <hr />
                  <div class="mb-3">
                    <label for="" class="fw-bold">Permissions</label>
                    <br />
                    <div
                      class="form-check form-check-inline"
                      v-for="(permission, index) in permissions"
                      :key="index"
                    >
                      <input
                        type="checkbox"
                        class="form-check-input"
                        v-model="form.permissions"
                        :value="permission.name"
                        :id="`check-${permission.id}`"
                      />
                      <label
                        :for="`check-${permission.id}`"
                        class="form-check-lable"
                        >{{ permission.name }}</label
                      >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <button
                        class="btn btn-primary shadow shadow-sm rounded-sm"
                        type="submit"
                      >
                        UPDATE
                      </button>
                      <button
                        class="btn btn-warning shadow shadow-sm rounded-sm ms-3"
                        type="reset"
                      >
                        RESET
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import LayoutApp from "../../../Layouts/App.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";

export default {
  layout: LayoutApp,

  components: {
    Head,
    Link,
  },

  props: {
    errors: Object,
    permissions: Array,
    role: Object,
  },

  // composition API
  setup(props) {
    // define from with reactive
    const form = reactive({
      name: props.role.name,
      permissions: props.role.permissions.map((obj) => obj.name),
    });

    // method submit
    const submit = () => {
      // send data to server
      Inertia.put(
        `/apps/roles/${props.role.id}`,
        {
          // data
          name: form.name,
          permissions: form.permissions,
        },
        {
          onSuccess: () => {
            Swal.fire({
              title: "Success!",
              text: "Role updated succesfully.",
              icon: "success",
              showConfirmButton: false,
              timer: 2000,
            });
          },
        }
      );
    };

    return {
      form,
      submit,
    };
  },
};
</script>