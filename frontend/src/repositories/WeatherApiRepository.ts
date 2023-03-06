import ApiService from "@/services/ApiService";
import type { AxiosPromise } from "axios";

enum Path {
  API_USERS = "user-weather",
  API_USER_WEATHER_DETAILS = "user-weather/:id",
}

class WeatherApiRepository {
  getUsers(per_page: number, page: number): AxiosPromise {
    return ApiService.query(Path.API_USERS, {
      params: { per_page, page },
    });
  }

  getUserWeatherDetails(userId: string): AxiosPromise {
    return ApiService.get(Path.API_USER_WEATHER_DETAILS.replace(":id", userId));
  }
}

export default new WeatherApiRepository();
