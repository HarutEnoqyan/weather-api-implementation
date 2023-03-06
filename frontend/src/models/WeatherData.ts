interface IWeatherData {
  temp: number;
  humidity: number;
  pressure: number;
  temp_max: number;
  temp_min: number;
  sea_level: number | null;
  feels_like: number;
  grnd_level: number | null;
}

export { type IWeatherData };
