'use client';

import { JobApplication } from 'protocols/external/job/job-application';
import { UserProps } from 'protocols/external/user/user';
import { Dispatch, ReactNode, SetStateAction, useMemo, useState } from 'react';
import { createContext } from 'use-context-selector';

type CoverLetterProps = {
	children: ReactNode;
};

type UserCoverLetterProps = {
	user_id: number;
} & Pick<UserProps, 'name' | 'email' | 'phone'>;

type JobCoverLetterProps = {
	jobId: number;
} & Pick<JobApplication, 'id' | 'status' | 'cover_letter'>;

type CoverLetter = {
	user: UserCoverLetterProps;
	jobApplication: JobCoverLetterProps;
};

type CoverLetterContextProps = {
	open: boolean;
	setOpen: Dispatch<SetStateAction<boolean>>;
	coverLetter: CoverLetter | null;
	setCoverLetter: Dispatch<SetStateAction<CoverLetter | null>>;
};

const defaultValues: CoverLetterContextProps = {
	open: false,
	setOpen: () => ({}),
	coverLetter: {} as CoverLetter,
	setCoverLetter: () => ({}),
};

export const CoverLetterContext = createContext(defaultValues);

export const CoverLetterProvider = ({ children }: CoverLetterProps) => {
	const [open, setOpen] = useState(false);
	const [coverLetter, setCoverLetter] = useState<CoverLetter | null>(null);

	const values = useMemo(
		() => ({
			open,
			setOpen,
			coverLetter,
			setCoverLetter,
		}),
		[open, coverLetter],
	);

	return (
		<CoverLetterContext.Provider value={values}>
			{children}
		</CoverLetterContext.Provider>
	);
};
