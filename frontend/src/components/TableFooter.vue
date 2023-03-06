<template>
  <div class="d-flex justify-content-between">
    <select v-model="perPage">
      <option
        v-for="(value, index) in perPageValues"
        :value="value"
        :key="index"
      >
        {{ value }}
      </option>
    </select>
    <nav aria-label="Page navigation example">
      <ul class="pagination mb-0">
        <li class="page-item">
          <span
            class="page-link"
            :class="page <= 1 ? 'disabled' : ''"
            @click="changePage(page - 1)"
            aria-label="Previous"
          >
            <span aria-hidden="true">&laquo;</span>
          </span>
        </li>
        <template
          v-for="(pageNumber, index) in Math.ceil(total / perPage)"
          :key="index"
        >
          <li
            class="page-item"
            :class="page === pageNumber ? 'active' : ''"
            @click="changePage(pageNumber)"
          >
            <a class="page-link" href="#">{{ pageNumber }}</a>
          </li>
        </template>
        <li class="page-item">
          <span
            class="page-link"
            :class="page >= lastPage ? 'disabled' : ''"
            aria-label="Next"
            @click="changePage(page + 1)"
          >
            <span aria-hidden="true">&raquo;</span>
          </span>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";

export default defineComponent({
  props: {
    total: { type: Number, default: 0 },
    currentPage: { type: Number, default: 1 },
    itemsPerPage: { type: Number, default: 5 },
  },
  emits: ["pagination-changed"],
  setup(props, { emit }) {
    const page = ref(props.currentPage);
    const perPage = ref(props.itemsPerPage);
    const lastPage = computed(() => props.total / perPage.value);
    const perPageValues = [5, 10, 20, 30, 50];
    const router = useRouter();
    const route = useRoute();

    watch(
      () => perPage.value,
      (value) => {
        changePage(1, false);
        router.push({
          query: {
            ...route.query,
            per_page: value,
            page: 1,
          },
        });
      }
    );

    const changePage = (newPage: number, addToUrl = true) => {
      page.value = newPage;
      if (addToUrl) {
        router.push({
          query: {
            ...route.query,
            page: newPage,
          },
        });
      }
      emit("pagination-changed", {
        currentPage: newPage,
        perPage: perPage.value,
      });
    };
    return {
      page,
      perPage,
      changePage,
      lastPage,
      perPageValues,
    };
  },
});
</script>
