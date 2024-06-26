import type { Meta, StoryObj } from '@storybook/react';

import { ResumeCard, ResumeCardProps } from './ResumeCard';
import { CompetenceItem } from 'components/CompetenceItem';

export default {
  title: 'Components/ResumeCard',
  component: ResumeCard,
  tags: ['autodocs'],
} as Meta<ResumeCardProps>;

export const Default: StoryObj<ResumeCardProps> = {
  args: {
    text: 'Competências',
    children: <CompetenceItem id={10} name="PHP" />,
  }
};
