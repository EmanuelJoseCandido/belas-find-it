import { TNullable, TDateTimeString } from "./ICommon";
import { ICategory } from "./ICategory";
import { IUser } from "./IUser";
import { IClaim } from "./IClaim";

// Tipos para status de itens
export type TTemStatus = "perdido" | "achado" | "resgatado";

// Interface para Itens
export interface IItem {
  id: number;
  ICategory_id: TNullable<number>;
  IUser_id: number;
  title: string;
  description: string;
  location: TNullable<string>;
  status: TTemStatus;
  image: TNullable<string>;
  found_date: TNullable<TDateTimeString>;
  created_at: TDateTimeString;
  updated_at: TNullable<TDateTimeString>;
  deleted_at: TNullable<TDateTimeString>;

  // Campos opcionais para relações
  ICategory?: TNullable<ICategory>;
  IUser?: IUser;
  IClaims?: IClaim[];
}

// DTO para Criação de Item
export interface ICreateItemDTO {
  title: string;
  description: string;
  ICategory_id?: TNullable<number>;
  location?: TNullable<string>;
  status: TTemStatus;
  image?: File | null; // Ao enviar para o servidor, usa-se File no frontend
  IUser_id: number;
  found_date?: TNullable<string>;
}

// DTO para Atualização de Item
export interface IUpdateItemDTO {
  title?: string;
  description?: string;
  ICategory_id?: TNullable<number>;
  location?: TNullable<string>;
  status?: TTemStatus;
  image?: File | null;
  found_date?: TNullable<string>;
}
