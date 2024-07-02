import api from 'services/api/api';
import { HttpResponse } from './common';

export type HttpPutParams = {
  url: string;
  body?: any;
  headers?: any;
};

export interface HttpPutClient {
  patch: (params: HttpPutParams) => Promise<HttpResponse>;
}

export class PatchClient implements HttpPutClient {
  async patch({ url, body }: HttpPutParams) {
    return await api.patch<HttpResponse>(url, body, { withCredentials: true });
  }
}
