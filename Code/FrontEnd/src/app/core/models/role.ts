import { BaseModel } from './baseModel';
export class Role extends BaseModel {
    id: number;
    role_name: string;
    role_name_ar: string;
    rights: Array<Object>;
}


