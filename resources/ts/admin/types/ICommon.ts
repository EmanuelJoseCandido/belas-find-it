// Tipos comuns compartilhados entre várias interfaces
export type TNullable<T> = T | null;
export type TDateTimeString = string;

// Tipos de respostas da API
export interface IPaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
  };
  links: {
    first: string;
    last: string;
    prev: TNullable<string>;
    next: TNullable<string>;
  };
}

export interface IApiResponse<T> {
  data: T;
  message?: string;
  success: boolean;
}
