import { TNullable, TDateTimeString } from "./ICommon";
import { IItem } from "./IItem";

// Interface para Categorias
export interface ICategory {
  id: number;
  name: string;
  created_at: TDateTimeString;
  updated_at: TNullable<TDateTimeString>;
  deleted_at: TNullable<TDateTimeString>;

  // Campos opcionais para relações
  items?: IItem[];
}

// DTO para Criação de Categoria
export interface ICreateICategoryDTO {
  name: string;
}

// DTO para Atualização de Categoria
export interface IUpdateICategoryDTO {
  name: string;
}
