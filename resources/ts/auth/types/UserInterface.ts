export interface IUser {
    id: number;
    name: string;
    email: string;
    role: string;
    token?: string;
}

export interface ILoginCredentials {
    email: string;
    password: string;
}

export interface IAuthResponse {
    data: IUser & { token: string };
}
