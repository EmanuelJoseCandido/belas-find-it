import { TNullable, TDateTimeString } from "./ICommon";
import { IItem } from "./IItem";

// Tipos para status de Contacto
export type TContactStatus =
  | "pendente"
  | "em_andamento"
  | "resolvido"
  | "encerrado";

// Interface para Contactos
export interface IContact {
  id: number;
  name: string;
  email: string;
  phone: TNullable<string>;
  subject: string;
  message: string;
  item_id: TNullable<number>;
  status: TContactStatus;
  created_at: TDateTimeString;
  updated_at: TNullable<TDateTimeString>;
  deleted_at: TNullable<TDateTimeString>;

  // Campos opcionais para relações
  item?: TNullable<IItem>;
}

// DTO para Criação de Contacto
export interface ICreateContactDTO {
  name: string;
  email: string;
  phone?: TNullable<string>;
  subject: string;
  message: string;
  item_id?: TNullable<number>;
}

// DTO para Atualização de Status de Contacto
export interface IUpdateContactStatusDTO {
  status: TContactStatus;
}
