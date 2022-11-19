## Contribution Guidelines

This document contains a set of guidelines to help you during the contribution process. We are happy to welcome all the contributions from anyone willing to improve/add new scripts to this project. Thank you for helping out and remember, **no contribution is too small.**

## Submitting Contributions

### Step 1: Fork the Project

Fork this Repository. This will create a Local Copy of this Repository on your Github Profile. Keep a reference to the original project in `upstream` remote.

```bash
git clone

git remote add upstream

```

### Step 2: Create a Branch

Create a new branch. Use its name to identify the issue your addressing.

```bash
git checkout -b <your_branch_name>
```

### Step 3: Work on the issue assigned

Work on the issue(s) assigned to you. You can also create your own Issues! Always remember to sync your copy before working.

```bash
git remote update
git checkout <your_branch_name>
git rebase upstream/master
```

### Step 4: Commit

Make changes in source code. When you're done with your work, add changes to the branch you've just created by:

```bash
git add .
```

To commit give a descriptive message for the convenience of reviewer by:

```bash
git commit -m "message"
```

### Step 5: Push

Push your awesome work to your remote repository:

```bash
git push -u origin <your_branch_name>
```

### Step 6: Pull Request

Finally, go to your repository in browser and click on `compare and pull requests`. Then add a title and description to your pull request that explains your contribution.

Voila! Your Pull Request has been submitted and will be reviewed by the moderators and merged.🥳