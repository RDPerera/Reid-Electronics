SET QUOTED_IDENTIFIER ON;

CREATE TABLE [#__gmapfp](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[nom] [nvarchar](200) NOT NULL DEFAULT '',
	[alias] [nvarchar](255) NOT NULL,
	[adresse] [nvarchar](200) NOT NULL DEFAULT '',
	[adresse2] [nvarchar](200) NOT NULL DEFAULT '',
	[ville] [nvarchar](200) NOT NULL DEFAULT '',
	[codepostal] [nvarchar](80) NOT NULL DEFAULT '',
	[pay] [nvarchar](200) NOT NULL DEFAULT '',
	[tel] [nvarchar](30) NOT NULL DEFAULT '',
	[tel2] [nvarchar](30) NOT NULL DEFAULT '',
	[fax] [nvarchar](30) NOT NULL DEFAULT '',
	[email] [nvarchar](100) NOT NULL DEFAULT '',
	[web] [nvarchar](200) NOT NULL DEFAULT '',
	[img] [nvarchar](100) NOT NULL DEFAULT '',
	[departement] [nvarchar](200) NOT NULL DEFAULT '',
	[album] [tinyint] NOT NULL DEFAULT 0,
	[intro] [nvarchar](max) NOT NULL,
	[message] [nvarchar](max) NOT NULL,
	[horaires_prix] [nvarchar](max) NOT NULL,
	[link] [nvarchar](200) NOT NULL DEFAULT '',
	[article_id] [bigint] NOT NULL DEFAULT 0,
	[icon] [nvarchar](100) NOT NULL DEFAULT '',
	[icon_label] [nvarchar](100) NOT NULL DEFAULT '',
	[affichage] [smallint] NOT NULL DEFAULT 0,
	[marqueur] [nvarchar](200) NOT NULL DEFAULT '',
	[glng] [nvarchar](20) NOT NULL DEFAULT '',
	[glat] [nvarchar](20) NOT NULL DEFAULT '',
	[gzoom] [nvarchar](2) NOT NULL DEFAULT '',
	[catid] [bigint] NOT NULL DEFAULT 0,
	[userid] [bigint] NOT NULL DEFAULT 0,
	[published] [smallint] NOT NULL DEFAULT 0,
	[checked_out] [smallint] NOT NULL DEFAULT 0,
	[metadesc] [nvarchar](max) NOT NULL,
	[metakey] [nvarchar](max) NOT NULL,
	[ordering] [int] NOT NULL DEFAULT 0,
 CONSTRAINT [PK_#__gmapfp_id] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY];

SET QUOTED_IDENTIFIER ON;

CREATE TABLE [#__gmapfp_personnalisation](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[nom] [nvarchar](200) NOT NULL DEFAULT '',
	[intro_carte] [nvarchar](max) NOT NULL,
	[conclusion_carte] [nvarchar](max) NOT NULL,
	[intro_detail] [nvarchar](max) NOT NULL,
	[conclusion_detail] [nvarchar](max) NOT NULL,
	[published] [smallint] NOT NULL DEFAULT 0,
 CONSTRAINT [PK_#__gmapfp_personnalisation_id] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY];

SET QUOTED_IDENTIFIER ON;

CREATE TABLE [#__gmapfp_marqueurs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[nom] [nvarchar](max) NOT NULL,
	[url] [nvarchar](max) NOT NULL,
	[published] [smallint] NOT NULL DEFAULT 0,
 CONSTRAINT [PK_#__gmapfp_marqueurs_id] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY];
