export interface IUser {
    id: number;
    name: string;
    email: string;
    phone: string;
    gender: TGenders;
    role: string;
    token?: string;
}

export type TGenders = "masculino" | "feminino" | "outro";
export type TTypeLogin = "email" | "phone";

export interface IRegisterUser extends Omit<IUser, "id" | "role"> {
    password: string;
    password_confirmation: string;
    terms: boolean;
}

export interface IProfileUser extends Omit<IUser, "id" | "role"> {}

export interface ILoginCredentials {
    email?: string;
    phone?: number;
    type: TTypeLogin;
    password: string;
}

export interface IAuthResponse {
    data: {
        data: IUser & { token: string };
    };
}
