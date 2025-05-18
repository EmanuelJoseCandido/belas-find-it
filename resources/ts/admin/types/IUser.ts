import { TNullable, TDateTimeString } from "./ICommon";
import { IItem } from "./IItem";
import { IClaim } from "./IClaim";

// Tipos para gênero de usuários
export type TGender = "masculino" | "feminino" | "outro";

// Tipos para perfis de usuário
export type TUserRole = "admin" | "moderator" | "IUser";

// Interface para Usuários
export interface IUser {
  id: number;
  name: string;
  slug: string;
  email: string;
  phone: string;
  email_verified_at: TNullable<TDateTimeString>;
  role: TUserRole;
  gender: TGender;
  is_blocked: boolean;
  is_login: boolean;
  is_tour: boolean;
  is_changed_password_once: boolean;
  created_at: TDateTimeString;
  updated_at: TNullable<TDateTimeString>;
  deleted_at: TNullable<TDateTimeString>;

  // Campos opcionais para relações
  items?: IItem[];
  IClaims?: IClaim[];
}

// DTO para Criação de Usuário
export interface ICreateIUserDTO {
  name: string;
  email: string;
  phone: string;
  gender: TGender;
  password: string;
  password_confirmation: string;
}

// DTO para Atualização de Usuário
export interface IUpdateIUserDTO {
  name?: string;
  phone?: string;
  gender?: TGender;
}

// DTO para Atualização de Senha
export interface IUpdatePasswordDTO {
  current_password: string;
  password: string;
  password_confirmation: string;
}
