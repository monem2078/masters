import { BaseModel } from './baseModel';

export class Rate extends BaseModel {
    id: number;
    user_id: number;
    master_id: number;
    rating: number;
    review: string;
    name: string;
    image_url: string;
    created_diff: string;
}
