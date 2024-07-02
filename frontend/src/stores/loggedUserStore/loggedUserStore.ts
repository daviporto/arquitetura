import { UserEnum, UserType } from 'protocols/external/user/user';
import { create } from 'zustand';
import { createJSONStorage, persist } from 'zustand/middleware';

export type User = {
	id: number;
	name: string;
	email: string;
	type: UserType;
};

export type LoggedUserProps = {
	id: number;
	name: string;
	email: string;
	type: UserType;
	setUser(user: Partial<User>): void;
};

export const useLoggedUserStore = create<LoggedUserProps>()(
	persist(
		(set, get) => ({
			id: 0,
			name: '',
			email: '',
			type: UserEnum.EMPLOYEE,
			setUser: (user) =>
				set(() => ({
					id: user.id || get().id,
					type: user.type || get().type,
					name: user.name || get().name,
					email: user.email || get().email,
				})),
		}),
		{
			name: '@find-me:user',
			storage: createJSONStorage(() => localStorage),
		},
	),
);
