import { TNullable, TDateTimeString } from "./ICommon";
import { IItem } from "./IItem";
import { IUser } from "./IUser";

// Tipos para status de reivindicações
export type TClaimStatus = "pendente" | "aprovado" | "rejeitado";

// Interface para Reivindicações
export interface IClaim {
  id: number;
  item_id: number;
  IUser_id: number;
  status: TClaimStatus;
  message: TNullable<string>;
  created_at: TDateTimeString;
  updated_at: TNullable<TDateTimeString>;
  deleted_at: TNullable<TDateTimeString>;

  // Campos opcionais para relações
  item?: IItem;
  IUser?: IUser;
}

// DTO para Criação de Reivindicação
export interface ICreateIClaimDTO {
  item_id: number;
  IUser_id: number;
  status: TClaimStatus;
  message?: TNullable<string>;
}

// DTO para Atualização de Reivindicação
export interface IUpdateIClaimDTO {
  status?: TClaimStatus;
  message?: TNullable<string>;
}
