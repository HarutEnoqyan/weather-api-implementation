<template>
  <div class="container pt-5">
    <div class="row">
      <div class="col-6 mx-auto">
        <table class="table table-striped">
          <thead>
            <tr>
              <th
                v-for="({ label }, index) in tableColumns"
                :key="index"
                v-html="label"
              ></th>
            </tr>
          </thead>
          <tbody>
            <template v-if="users.length">
              <tr v-for="user in users" :key="user.id">
                <td v-for="({ column }, index) in tableColumns" :key="index">
                  <template v-if="column === 'details'">
                    <button
                      type="button"
                      class="btn btn-warning"
                      data-bs-toggle="modal"
                      data-bs-target="#details-modal"
                      @click="showDetails(user.id, user.email)"
                    >
                      View Details
                    </button>
                  </template>
                  <template v-else>{{ user[column] || "-" }}</template>
                </td>
              </tr>
            </template>
            <tr v-else>
              <td class="text-center">"No user data loaded"</td>
            </tr>
          </tbody>
        </table>
        <table-footer
          :current-page="pagination.currentPage"
          :items-per-page="pagination.perPage"
          :total="pagination.total"
          @paginationChanged="paginationChanged"
        />
      </div>
    </div>
    <details-modal ref="userWeatherDetailsModal" />
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import weatherApiRepository from "@/repositories/WeatherApiRepository";
import { onBeforeMount } from "vue";
import type { IUser } from "@/models/User";
import TableFooter from "@/components/TableFooter.vue";
import { useRoute } from "vue-router";
import DetailsModal from "@/components/DetailsModal.vue";

export default defineComponent({
  components: { TableFooter, DetailsModal },
  setup() {
    const users = ref<IUser[]>([]);
    const route = useRoute();
    const pagination = ref({
      perPage: Number(route.query.per_page) || 10,
      currentPage: Number(route.query.page) || 1,
      total: 0,
    });
    const userWeatherDetailsModal = ref();

    const tableColumns = [
      { label: "Id", column: "id" },
      { label: "Email", column: "email" },
      { label: "Temperature &#8451;", column: "current_temp" },
      { label: "Details", column: "details" },
    ];
    onBeforeMount(() => {
      fetchUserData();
    });

    const fetchUserData = () => {
      weatherApiRepository
        .getUsers(pagination.value.perPage, pagination.value.currentPage)
        .then(({ data }) => {
          users.value = data.data;
          pagination.value.total = data.total;
        });
    };

    const paginationChanged = (changedPagination: Record<string, number>) => {
      pagination.value = {
        ...pagination.value,
        ...changedPagination,
      };
      fetchUserData();
    };

    const showDetails = (id: number, email: string) => {
      if (userWeatherDetailsModal.value) {
        userWeatherDetailsModal.value.fetchUserWeatherDetails(id, email);
      }
    };

    return {
      tableColumns,
      users,
      pagination,
      paginationChanged,
      showDetails,
      userWeatherDetailsModal,
    };
  },
});
</script>
