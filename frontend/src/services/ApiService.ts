import axios, { type AxiosRequestConfig, type AxiosResponse } from "axios";

class ApiService {
  private static baseUrl = import.meta.env.VITE_APP_API_URL;

  public static init() {
    axios.defaults.baseURL = ApiService.baseUrl;
  }

  /**
   * @description send the GET HTTP request
   * @param resource: string
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static query(
    resource: string,
    params: AxiosRequestConfig
  ): Promise<AxiosResponse> {
    return axios.get(resource, params);
  }

  /**
   * @description send the GET HTTP request
   * @param resource: string
   * @param slug: string
   * @returns Promise<AxiosResponse>
   */
  public static get(
    resource: string,
    slug = "" as string
  ): Promise<AxiosResponse> {
    const url = slug.length ? `${resource}/${slug}` : resource;
    return axios.get(url);
  }

  /**
   * @description set the POST HTTP request
   * @param resource: string
   * @param data
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static post(
    resource: string,
    data?: FormData | Record<string, string | number | null>,
    params?: AxiosRequestConfig
  ): Promise<AxiosResponse> {
    return axios.post(`${resource}`, data, params);
  }

  /**
   * @description send the UPDATE HTTP request
   * @param resource: string
   * @param slug: string
   * @param data
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static update(
    resource: string,
    slug: string,
    data?: FormData | Record<string, string | number | null>,
    params?: AxiosRequestConfig
  ): Promise<AxiosResponse> {
    const url = slug.length ? `${resource}/${slug}` : resource;

    return axios.put(url, data, params);
  }

  /**
   * @description Send the PUT HTTP request
   * @param resource: string
   * @param data
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static put(
    resource: string,
    data?: FormData | Record<string, string | number | null>,
    params?: AxiosRequestConfig
  ): Promise<AxiosResponse> {
    if (data instanceof FormData) data.append("_method", "PUT");
    else if (data) data["_method"] = "PUT";
    return axios.post(`${resource}`, data, params);
  }

  /**
   * @description Send the DELETE HTTP request
   * @param resource: string
   * @returns Promise<AxiosResponse>
   */
  public static delete(resource: string): Promise<AxiosResponse> {
    return axios.delete(resource);
  }
}

export default ApiService;
