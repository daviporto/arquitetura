import { HttpResponse } from 'msw';
import api from 'services/api/api';

export type HttpGetParams = {
  url: string;
  query?: string;
};

export interface HttpGetClient {
  get: (params: HttpGetParams) => Promise<HttpResponse>;
}

export class GetClient implements HttpGetClient {
  async get<T>({ url, query }: HttpGetParams): Promise<T> {
    return await api.get(`${query ? url + query : url}`, {
      withCredentials: true,
    });
  }
}
