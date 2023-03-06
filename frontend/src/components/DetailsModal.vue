<template>
  <div>
    <div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="details-modal-label">
              User: {{ userEmail }}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <p v-for="(detail, key, index) in details" :key="index">
              <b>{{ formatKey(key) }}</b> : {{ detail }}
            </p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import weatherApiRepository from "@/repositories/WeatherApiRepository";

export default defineComponent({
  setup() {
    const userEmail = ref("");
    const details = ref({});
    const fetchUserWeatherDetails = (id: string, email: string) => {
      userEmail.value = email;
      weatherApiRepository.getUserWeatherDetails(id).then(({ data }) => {
        details.value = data.data;
      });
    };

    const formatKey = (key: string) => {
      return key.replace("_", " ");
    };
    return {
      fetchUserWeatherDetails,
      formatKey,
      userEmail,
      details,
    };
  },
});
</script>
