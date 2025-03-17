export interface IUser {
    id: number;
    name: string;
    email: string;
    role: string;
    token?: string;
}

export type TGenders = "masculino" | "feminino" | "outro";

export interface IRegisterUser extends Omit<IUser, "id" | "role"> {
    phone: string;
    gender: TGenders;
    password: string;
    password_confirmation: string;
    terms: boolean;
}

export interface ILoginCredentials {
    email: string;
    password: string;
}

export interface IAuthResponse {
    data: IUser & { token: string };
}
