export interface IUser {
  id: number;
  name: string;
  email: string;
  phone: string;
  gender: TGenders;
  role: string;
  token?: string;
  isAuthenticated: boolean;
}

export type TGenders = "masculino" | "feminino" | "outro";
export type TTypeLogin = "email" | "phone";

export interface IRegisterUser
  extends Omit<IUser, "id" | "role" | "isAuthenticated"> {
  password: string;
  password_confirmation: string;
}

export interface IUpdateProfileUser
  extends Omit<IUser, "id" | "role" | "isAuthenticated"> {
  current_password?: string;
  password?: string;
  password_confirmation?: string;
}

export interface ILoginCredentials {
  email?: string;
  phone?: number;
  type: TTypeLogin;
  password: string;
  remember: boolean;
}

export interface IAuthResponse {
  data: {
    data: IUser & { token: string };
  };
}

export interface IFormValues {
  name: string;
  email: string;
  phone: string;
  gender: TGenders;
  currentPassword?: string;
  newPassword?: string;
  passwordConfirmation?: string;
}
