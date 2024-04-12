'use client';
import { RegisterForm } from 'components/RegisterForm/RegisterForm';
import { Auth } from 'templates/Auth/Auth';

export default async function CandidatesPage() {
  return (
    <Auth>
      <RegisterForm />
    </Auth>
  );
}
